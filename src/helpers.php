<?php

if ( ! function_exists('transform')) {
    function transform()
    {
        $args = func_get_args();

        if ( ! isset($args[0]) && $args[0] != null)
            throw new \Themsaid\Transformers\TransformerException('Argument 1 of the transform function is missing.');

        if ($args[0] == null)
            return null;

        if ($args[0] instanceof \Illuminate\Database\Eloquent\Model) {
            $model = $args[0];
        } else if ($args[0] instanceof \Illuminate\Support\Collection) {
            $model = $args[0]->first();
        } else {
            throw new \Themsaid\Transformers\TransformerException('Argument 1 of the transform function must be an instance of Model or Collection.');
        }

        // The only case where a model is not found is when the method
        // receives an empty collection, for this we only need
        // to return an empty array.
        if ( ! $model)
            return [];

        $reflector = new ReflectionClass($model);
        $transformerName = "{$reflector->getShortName()}Transformer";
        $transformerPath = config('modelTransformers.transformers_namespace') . '\\' . $transformerName;

        try {
            return forward_static_call_array(
                [
                    $transformerPath,
                    'transform'
                ],
                $args
            );
        } catch (ErrorException $e) {
            throw new \Themsaid\Transformers\TransformerException($transformerPath . ' can not be found.');
        }
    }
}