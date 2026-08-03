<script>
    (function ($) {
        "use strict";

        const selectors = {
            country: @json($countrySelect),
            location: @json($locationSelect),
            area: @json($areaSelect)
        };

        const locationOptions = @json($locationOptions ?? []);
        const areaOptions = @json($areaOptions ?? []);
        const selected = {
            country: @json((string) ($selectedCountryId ?? '')),
            location: @json((string) ($selectedLocationId ?? '')),
            area: @json((string) ($selectedAreaId ?? ''))
        };
        const legacy = {
            location: @json((string) ($legacyLocationId ?? '')),
            country: @json((string) ($legacyCountryId ?? ''))
        };
        const localeDirection = @json(app()->getLocale() == 'ar' ? 'rtl' : 'ltr');

        const select2Config = {
            width: '100%',
            dir: localeDirection
        };

        const $country = $(selectors.country);
        const $location = $(selectors.location);
        const $area = $(selectors.area);

        [$country, $location, $area].forEach(($select) => {
            if ($select.length && $.fn.select2) {
                $select.select2(select2Config);
            }
        });

        function appendOptions($select, placeholder, options, selectedValue) {
            const selectedValueText = String(selectedValue || '');

            $select.empty().append(new Option(placeholder, ''));

            options.forEach((option) => {
                const value = String(option.id);
                const isSelected = value === selectedValueText;
                $select.append(new Option(option.text, value, isSelected, isSelected));
            });

            if (!options.some((option) => String(option.id) === selectedValueText)) {
                $select.val('');
            }

            $select.trigger('change.select2');
        }

        function locationsFor(countryId) {
            const selectedCountryId = String(countryId || '');

            if (!selectedCountryId) {
                return [];
            }

            return locationOptions.filter((location) => {
                const locationCountryId = location.country_id === null ? '' : String(location.country_id);
                const isCurrentLegacyLocation = legacy.location && String(location.id) === legacy.location && selectedCountryId === legacy.country;

                return locationCountryId === selectedCountryId || isCurrentLegacyLocation;
            });
        }

        function areasFor(locationId) {
            return areaOptions.filter((area) => String(area.location_id) === String(locationId || ''));
        }

        function populateAreas(locationId, selectedAreaId) {
            appendOptions($area, @json(__('Select Area')), areasFor(locationId), selectedAreaId);
        }

        function populateLocations(countryId, selectedLocationId, selectedAreaId) {
            appendOptions($location, @json(__('Select City')), locationsFor(countryId), selectedLocationId);
            populateAreas($location.val(), selectedAreaId);
        }

        let currentCountryId = String($country.val() || selected.country || '');

        populateLocations(currentCountryId, selected.location, selected.area);

        $country.on('change', function () {
            const countryId = String($(this).val() || '');

            if (countryId === currentCountryId) {
                return;
            }

            currentCountryId = countryId;
            populateLocations(countryId, '', '');
        });

        $location.on('change', function () {
            populateAreas($(this).val(), '');
        });
    })(jQuery);
</script>
