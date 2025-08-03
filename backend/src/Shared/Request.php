<?php
namespace SalesAppApi\Shared;

use Exception;
use DateTime;

Class Request{
    private $headers = [];

    public function __construct(
      private $validatedData = [],
      private $errors = [],
      private $requestInput = []
    )
    {
        $this->headers = getallheaders();

        $this->requestInput = (json_decode(file_get_contents('php://input'),true));
        if(empty($this->requestInput)){
            $this->requestInput = $_REQUEST;
        }
        if(empty($this->requestInput)){
            $this->requestInput = [];
        }
    }

    public function validate(Array $rules, Array $customErrors = []){
        $preparedRules = [];
        foreach($rules as $field => $rules){
            $preparedRules[] = $this->prepareRules($field,$rules);
        }

        foreach($preparedRules as $preparedRule){
            $fieldIsset = array_key_exists($preparedRule['field'], $this->requestInput);
            $fieldValue = array_key_exists($preparedRule['field'], $this->requestInput) ? $this->requestInput[$preparedRule['field']] : null;

            if(array_key_exists('nullable', $preparedRule['rules']) and $fieldIsset and $fieldValue == null){
                continue;
            }

            if(array_key_exists('required', $preparedRule['rules']) and (!$fieldIsset || empty($fieldValue))){
                $this->errors[] = "O campo [".$preparedRule['field']."] é requerido";
                continue;
            }

            foreach($preparedRule['rules'] as $rule => $params){
                if(in_array($rule,['required','nullable'])){
                    continue;
                }

                $ruleParams = $this->getRuleParams($rule);

                if(!$this->{$ruleParams['method']}($fieldValue,$params['attribute'])){     
                    $message = $ruleParams['validation_message'];
                    $message = str_replace('{{field}}',$preparedRule['field'],$message);
                    $message = str_replace('{{attribute}}',$params['attribute'] ?? '',$message);
                    $this->errors[] = (!empty($customErrors[$preparedRule['field'].".".$params['rule']])) ? $customErrors[$preparedRule['field'].".".$params['rule']] : $message;
                }
            }

            $this->validatedData[$preparedRule['field']] = $fieldValue;
        }

        if(!empty($this->getErrors())){
            return [];
        }

        return $this->validatedData;

    }

    public function getErrors(){
        return $this->errors;
    }

    public function validateRequired($value){
        if(empty($value)){
            return false;
        }
        return true;
    }

    public function validateInt($value){
        if (filter_var($value, FILTER_VALIDATE_INT) !== false) {
            return true;
        }
        return false;
    }

    public function validateNullable($value){
        return true;
    }

    public function validateMin($value,$attribute){
        if ($this->validateInt($value)){
            return ($value < (int)$attribute) ? false : true;
        }
    }

    public function validateMax($value,$attribute){
        if ($this->validateInt($value)){
            return ($value > (int)$attribute) ? false : true;
        }
    }

    public function validateEmail($value){
        if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            return false;
        }
        return true;
    }

    public function validateArray($value){
        if(is_array($value)){
            return true;
        }
        return false;
    }

    public function validateDecimal($value,$attribute){
        if (filter_var($value, FILTER_VALIDATE_FLOAT) === false){
            return false;
        }

        $attribute = explode(",",$attribute);
        
        if(count($attribute) != 2){
            throw new Exception("Filtro decimal inválido", 422);
        }

        $value = explode(".",$value);

        if(count($value) == 1){
            return (strlen($value[0]) <= ($attribute[0] - $attribute[1])) ? true : false;
        }

        return (strlen($value[0]) <= ($attribute[0] - $attribute[1]) and (strlen($value[1]) <= $attribute[1])) ? true : false;
    }

    public function validateBetween($value,$attribute){
        $attribute = explode(",",$attribute);
        
        if(count($attribute) != 2){
            throw new Exception("Parametros do between inválidos. ex. between:1,10", 422);
        }

        if($value <= (float)$attribute[1] and $value >= (float)$attribute[0]){
            return true;
        }

        return false;
    }

    public function validateDate($value,$attribute){
        $data = DateTime::createFromFormat($attribute,$value);
        return ($data && $data->format($attribute) === $value) ? true : false;
    }

    public function validateBool($value){
        if (filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === NULL){
            return false;
        }
        return true;
    }

    public function validateRegexp($value,$attribute){
        if(filter_var($value,FILTER_VALIDATE_REGEXP, ["options" => ["regexp"=> $attribute ]])){
            return true;
        }

        return false;
    }

    public function validateString($value) {
        return is_string($value);
    }

    public function prepareRules($field,$rules){
        if(empty($field)){
            throw new Exception("O nome do campo deve ser informado", 422);
        }

        if(empty($rules)){
            return [
                'field' => $field,
                'rules' => [],
            ];
        }
        
        $rules = explode("|",$rules);
        $arrayParsedRules = [];

        foreach($rules as $rule){
            $parsedRule = $this->parseStringRule($rule);
            $this->validateRuleFormat($parsedRule['rule'],$parsedRule['attribute']);
            $arrayParsedRules[$parsedRule['rule']] = $parsedRule;
        }

        return [
            'field' => $field,
            'rules' => $arrayParsedRules,
        ];
    }

    public function parseStringRule($rule){
        if(!empty(strpos("XXX".$rule,":"))){
            [$rule,$attribute] = explode(":",$rule);
        }

        return [
            'rule' => $rule,
            'attribute' => $attribute ?? null
        ];
    }

    public function getRuleParams($params){
        $validationTypes  = [
            'integer' => [
                "method" => "validateInt",
                "attribute_required" => false,
                "validation_message" => "O valor do campo [{{field}}] deve ser inteiro.",
            ],
            'numeric' => [
                "method" => "validateDecimal",
                "attribute_required" => true,
                "validation_message" => "O valor do campo [{{field}}] deve ser numeric com formato [{{attribute}}].",
            ],
            'email' => [
                "method" => "validateEmail",
                "attribute_required" => false,
                "validation_message" => "O valor do campo [{{field}}] deve ser um e-mail.",
            ],
            'array' => [
                "method" => "validateArray",
                "attribute_required" => false,
                "validation_message" => "O valor do campo [{{field}}] deve ser array.",
            ],
            'between' => [
                "method" => "validateBetween",
                "attribute_required" => true,
                "validation_message" => "O valor do campo [{{field}}] deve ser entre [{{attribute}}] .",
            ],          
            'min' => [
                "method" => "validateMin",
                "attribute_required" => true,
                "validation_message" => "O valor mínimo do campo [{{field}}] é [{{attribute}}].",
            ],
            'max' => [
                "method" => "validateMax",
                "attribute_required" => true,
                "validation_message" => "O valor máximo do campo [{{field}}] é [{{attribute}}].",
            ],
            'date' => [
                "method" => "validateDate",
                "attribute_required" => true,
                "validation_message" => "O valor do campo [{{field}}] deve ter o formato [{{attribute}}].",
            ],
            'boolean' => [
                "method" => "validateBool",
                "attribute_required" => false,
                "validation_message" => "O valor do campo [{{field}}] deve ser boolean.",
            ],           
            'regex' => [
                "method" => "validateRegexp",
                "attribute_required" => true,
                "validation_message" => "O valor do campo [{{field}}] é inválido (regexp).",
            ],
            'required_with' => [
                "method" => "validateInt",
                "attribute_required" => true,
                "validation_message" => "O valor do campo [{{field}}] deve ser inteiro.",
            ],
            'string' => [
                "method" => "validateString",
                "attribute_required" => false,
                "validation_message" => "O campo [{{field}}] deve ser uma string.",
            ],
            'nullable' => [
                "method" => "validateNullable",
                "attribute_required" => false,
                "validation_message" => "", // po
            ],
            'required' => [
                "method" => "validateRequired",
                "attribute_required" => false,
                "validation_message" => "O campo [{{field}}] é obrigatório.",
            ],
        ];

        return (!empty($validationTypes[$params])) ? $validationTypes[$params] : null;
    }

    public function validateRuleFormat($rule ,$attribute){
        $ruleValidated = $this->getRuleParams($rule);

        if(empty($ruleValidated['method'])){
            throw new Exception("A validação é inválida ou não suportada [".$rule."]", 422);
        }
        if($ruleValidated['attribute_required'] and empty($attribute)){
            throw new Exception("A validação [".$rule."] requer parametros ", 422);
        }
        if(empty($ruleValidated['attribute_required']) and !empty($attribute)){
            throw new Exception("A validação [".$rule."] não requer parametros ", 422);
        }   

        return true;
    }

    public function header(string $key): ?string
    {
        return $this->headers[$key] ?? null;
    }
}
