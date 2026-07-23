<?php
$files = glob(__DIR__ . '/app/Models/*.php');
foreach ($files as $file) {
    $content = file_get_contents($file);
    
    // Remove "use App\Traits\Searchable;"
    $content = str_replace("use App\\Traits\\Searchable;\n", "", $content);
    $content = str_replace("use App\\Traits\\Searchable;\r\n", "", $content);
    
    // Remove ", Searchable" from "use HasFactory, GlobalStatus, Searchable;"
    $content = str_replace(", Searchable", "", $content);
    
    // Replace "use HasFactory, Searchable;" with "use HasFactory;"
    $content = str_replace("use HasFactory, Searchable;", "use HasFactory;", $content);
    $content = str_replace("use HasFactory, Searchable\r\n", "use HasFactory\r\n", $content);
    
    file_put_contents($file, $content);
}
echo "Done";
