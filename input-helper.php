<?php

function create_input($name, $label, $type, $errors)
{
    $value='';
    if (isset($_POST[$name]) and !empty($_POST[$name])) {
        $value = $_POST[$name];
    }

    $isError=isset($errors[$name])&& !empty($errors[$name]);
    $valid= $isError ? 'is-invalid' :'';
    print <<<END
            <div class="form-group">
                <label for="$name">$label</label>
                <input type="$type"
                       class="form-control $valid"
                       id="$name"
                       name="$name"
                       value="$value"/>
            
    END;
    if($isError)
        print <<<ERROR
            <div class="invalid-feedback d-block">
                $errors[$name]
            </div>
    ERROR;
    echo "</div>";
}