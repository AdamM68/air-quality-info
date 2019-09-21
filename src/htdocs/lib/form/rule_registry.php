<?php
namespace AirQualityInfo\Lib\Form;

class RuleRegistry {

    private $rules = array();

    public function __construct() {
        $this->rules['required'] = new Rule(function($value, $options) {
            return $value != null && strlen($value) > 0;
        }, "%s is required");
        $this->rules['minLength'] = new Rule(function($value, $options) {
            return $value != null && strlen($value) >= $options;
        }, "%s should be at least %d characters long");
        $this->rules['sameAs'] = new Rule(function($value, $options) {
            return $value === $_POST[$options];
        }, "%s should be the same as %s");
        $this->rules['numeric'] = new Rule(function($value, $options) {
            return is_numeric($value) && $value >= 0;
        }, "%s should be a number");
        $this->rules['regexp'] = new Rule(function($value, $options) {
            return empty($value) || preg_match($options['pattern'], $value);
        }, "%s contains invalid characters");
        $this->rules['range'] = new Rule(function($value, $options) {
            if (empty($value)) {
                return true;
            }
            if (!is_numeric($value)) {
                return false;
            }
            if (isset($options['min']) && $value < $options['min']) {
                return false;
            }
            if (isset($options['max']) && $value > $options['max']) {
                return false;
            }
            return true;
        }, "%s is out of range");
    }

    public function getRule($type) {
        if (isset($this->rules[$type])) {
            return $this->rules[$type];
        } else {
            throw new \Exception("Invalid rule type: $type");
        }
    }
}
?>