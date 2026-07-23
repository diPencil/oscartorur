<?php
$code = "
    public function getNameAttribute(\$value)
    {
        return \$this->name_ar ?: \$value;
    }
";
$files = ['core/app/Models/RoomType.php', 'core/app/Models/Seminar.php', 'core/app/Models/Plan.php'];
foreach ($files as $f) {
    if (file_exists($f)) {
        $content = file_get_contents($f);
        if (strpos($content, 'getNameAttribute') === false) {
            $content = preg_replace('/(use HasFactory;)/', "$1\n$code", $content);
            file_put_contents($f, $content);
            echo "Updated $f\n";
        }
    }
}
?>
