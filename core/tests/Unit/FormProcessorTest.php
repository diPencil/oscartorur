<?php

namespace Tests\Unit;

use App\Lib\FormProcessor;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class FormProcessorTest extends TestCase
{
    public function test_it_builds_rules_from_array_form_configuration(): void
    {
        $rules = (new FormProcessor())->valueValidation([
            'receipt' => [
                'name' => 'Receipt',
                'label' => 'receipt',
                'is_required' => 'required',
                'type' => 'file',
                'extensions' => 'jpg,png,pdf',
            ],
            'bank' => [
                'name' => 'Bank',
                'label' => 'bank',
                'is_required' => 'optional',
                'type' => 'select',
                'options' => ['A', 'B'],
            ],
        ]);

        $this->assertSame(['required', 'mimes:jpg,png,pdf'], $rules['receipt']);
        $this->assertSame(['nullable', 'in:A,B'], $rules['bank']);
    }

    public function test_it_builds_rules_from_json_string_form_configuration(): void
    {
        $rules = (new FormProcessor())->valueValidation(json_encode([
            [
                'name' => 'Transaction ID',
                'label' => 'transaction_id',
                'is_required' => 'required',
                'type' => 'text',
            ],
        ]));

        $this->assertSame(['required'], $rules['transaction_id']);
    }

    public function test_it_builds_rules_from_collection_form_configuration(): void
    {
        $rules = (new FormProcessor())->valueValidation(new Collection([
            [
                'name' => 'Email',
                'label' => 'email',
                'is_required' => 'required',
                'type' => 'email',
            ],
        ]));

        $this->assertSame(['required', 'email'], $rules['email']);
    }

    public function test_it_builds_rules_from_json_object_form_configuration(): void
    {
        $rules = (new FormProcessor())->valueValidation(json_encode([
            'name' => 'Website',
            'label' => 'website',
            'is_required' => 'optional',
            'type' => 'url',
        ]));

        $this->assertSame(['nullable', 'url'], $rules['website']);
    }

    public function test_it_builds_rules_from_double_encoded_json_configuration(): void
    {
        $rules = (new FormProcessor())->valueValidation(json_encode(json_encode([
            [
                'name' => 'Amount',
                'label' => 'amount',
                'is_required' => 'required',
                'type' => 'number',
            ],
        ])));

        $this->assertSame(['required', 'integer'], $rules['amount']);
    }

    public function test_it_normalizes_option_and_extension_legacy_shapes(): void
    {
        $processor = new FormProcessor();

        $rules = $processor->valueValidation([
            ['name' => 'Choice A', 'label' => 'choice_a', 'is_required' => 'optional', 'type' => 'select', 'options' => ['A', 'B']],
            ['name' => 'Choice B', 'label' => 'choice_b', 'is_required' => 'optional', 'type' => 'select', 'options' => json_encode(['C', 'D'])],
            ['name' => 'Choice C', 'label' => 'choice_c', 'is_required' => 'optional', 'type' => 'select', 'options' => 'E,F'],
            ['name' => 'Choice D', 'label' => 'choice_d', 'is_required' => 'optional', 'type' => 'select', 'options' => null],
            ['name' => 'File A', 'label' => 'file_a', 'is_required' => 'optional', 'type' => 'file', 'extensions' => ['jpg', 'png']],
            ['name' => 'File B', 'label' => 'file_b', 'is_required' => 'optional', 'type' => 'file', 'extensions' => json_encode(['pdf', 'doc'])],
            ['name' => 'File C', 'label' => 'file_c', 'is_required' => 'optional', 'type' => 'file', 'extensions' => 'csv,xlsx'],
            ['name' => 'File D', 'label' => 'file_d', 'is_required' => 'optional', 'type' => 'file', 'extensions' => null],
        ]);

        $this->assertSame(['nullable', 'in:A,B'], $rules['choice_a']);
        $this->assertSame(['nullable', 'in:C,D'], $rules['choice_b']);
        $this->assertSame(['nullable', 'in:E,F'], $rules['choice_c']);
        $this->assertSame(['nullable'], $rules['choice_d']);
        $this->assertSame(['nullable', 'mimes:jpg,png'], $rules['file_a']);
        $this->assertSame(['nullable', 'mimes:pdf,doc'], $rules['file_b']);
        $this->assertSame(['nullable', 'mimes:csv,xlsx'], $rules['file_c']);
        $this->assertSame(['nullable'], $rules['file_d']);
    }

    public function test_it_rejects_malformed_non_empty_form_configuration(): void
    {
        $this->expectException(ValidationException::class);

        (new FormProcessor())->valueValidation('not-json');
    }

    public function test_empty_form_configuration_returns_empty_rules(): void
    {
        $this->assertSame([], (new FormProcessor())->valueValidation(''));
        $this->assertSame([], (new FormProcessor())->valueValidation(null));
    }
}
