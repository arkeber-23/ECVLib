<?php

namespace Kernel\Core\Libs;

trait ValidateForm
{
    public function validate(array $rules, array $settings = []): void
    {
        $errors = [];

        foreach ($rules as $key => $rule) {
            $value = $this->input($key);
            $this->oldValue($key, $value);

            $rule = explode('|', $rule);
           

            foreach ($rule as $r) {
                 
          
                $r = explode(':', $r);
                echo "<pre>";
                print_r($r) ;
                echo "</pre>";

                $matchRule = trim($r[0]);
                $matchValue  = trim($r[1] ?? 0);

                switch ($matchRule) {
                    case 'required':
                        if (empty($value)) {
                            $errors[$key] = $this->setMessage($settings, $key, 'required', 'The field is required.');
                        }
                        break;
                    case 'min':
                        if (strlen($value) < $matchValue) {
                            $errors[$key] = $this->setMessage($settings, $key, 'min', 'The field ' . $key . ' must have at least ' . $matchValue . ' characters.');
                        }
                        break;
                    case 'max':
                        if (strlen($value) > $matchValue) {
                            $errors[$key] = $this->setMessage($settings, $key, 'max', 'The field ' . $key . ' must be a maximum ' . $matchValue . ' characters.');
                        }
                        break;
                    case 'email':
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $errors[$key] = $this->setMessage($settings, $key, 'email', 'The field ' . $key . ' must be a valid email address.');
                        }
                        break;
                    case 'numeric':
                        if (!is_numeric($value)) {
                            $errors[$key] = $this->setMessage($settings, $key, 'numeric', 'The field ' . $key . ' must be a number.');
                        }
                        break;
                }
            }
        }

        if (!empty($errors) && count($errors) > 0) {
            $rute = rtrim($_SERVER['HTTP_REFERER'], '/');
            $_SESSION['form_errors'] = $errors;
            header('Location: ' . $rute);
            exit;
        } else {
            unset($_SESSION['form_errors']);
            unset($_SESSION['old_values']);
        }
    }

    public function oldValue(string $key, string $value): void
    {
        $_SESSION['old_values'][$key] = $value;
    }

    private function setMessage(array $settings = [], string $key = '', string $rule = 'required', string $defaultMessage = ''): string
    {
        if (isset($settings["$key.$rule"])) {
            return $settings["$key.$rule"];
        }
        return $defaultMessage;
    }
}
