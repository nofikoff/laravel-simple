<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Opis\JsonSchema\Schema;
use Opis\JsonSchema\ValidationResult;
use Opis\JsonSchema\Validator;

use App\Exceptions\ApiHttpException;
use App\Utils\SchemaLoader;


class JsonValidate
{
    /**
     * Валидируем выходящий запрос по JSON-схеме.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $schemaPath путь, по которому лежит файл схемы
     * @return mixed
     * @throws \Exception
     */
    public function handle($request, Closure $next, $schemaPath)
    {
        $schema = SchemaLoader::loadSchema($schemaPath, true);
        $this->validateRequest($request, $schema);

        return $next($request);
    }

    /**
     * @param Request $request
     * @param \stdClass $schema
     */
    public function validateRequest(Request $request, $schema)
    {
        $validator = new Validator();
        $validator->setGlobalVars((array)[
            'APP_DEBUG' => config('app.debug') ? 'true' : 'false',
        ]);

        $data = json_decode($request->getContent());
        $validationSchema = new Schema($schema);

        $result = $validator->schemaValidation($data, $validationSchema);
        if (!$result->isValid()) {
            throw new ApiHttpException(400, 'The submitted data has not been validated', [
                'validationError' => json_encode($this->getValidationErrors($result)),
            ]);
        }
    }

    private function getValidationErrors(ValidationResult $result)
    {
        function getErrorInfo($error)
        {
            $errors = [];
            if ($error->subErrorsCount()) {
                foreach ($error->subErrors() as $subError) {
                    $errors = array_merge($errors, getErrorInfo($subError));
                }
            } else {
                $msg = [];
                foreach ($error->keywordArgs() as $key => $value) {
                    $msg[] = $key . ' ' . (is_array($value) ? join(', ', $value) : is_null($value) ? "null" : $value);
                }
                $errors[] = join('\\', $error->dataPointer()) . ': ' . join(', ', $msg);
            }

            return $errors;
        }

        $errors = [];
        foreach ($result->getErrors() as $error) {
            $errors = array_merge($errors, getErrorInfo($error));
        }

        return $errors;
    }
}
