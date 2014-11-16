<?php
namespace Core\Core;

/**
* Base controller abstract class.
* Extend to get access to app main container and common functions.
*
* @author Milos Kajnaco <miloskajnaco@gmail.com>
*/
abstract class Controller extends ContainerAware
{
    /**
    * Get post value from request object.
    *
    * @param string
    * @return mixed
    */
    protected function post($key = null)
    {
        if ($key === null) {
            return $this->app['Request']->post->all();
        }
        return $this->app['Request']->post->get($key);
    }

    /**
    * Render output for display.
    *
    * @param string
    * @param array
    */
    protected function render($view, $data = [])
    {
        // Extract variables.
        extract($data);

        // Start buffering.
        ob_start();

        // Load view file (root location is declared in APPVIEW constant).
        include APPVIEW.$view.'.php';

        // Append to output body.
        $this->app['Response']->writeBody(ob_get_contents());
        ob_end_clean();
    }

    /**
    * Buffer output and return it as string.
    *
    * @param string
    * @param array
    * @return string
    */
    protected function buffer($view, $data = [])
    {
        // Extract variables.
        extract($data);

        // Start buffering.
        ob_start();

        // Load view file (root location is declared in APPVIEW constant).
        include APPVIEW.$view.'.php';

        // Return string.       
        $buffer = ob_get_contents();
        ob_end_clean();
        return $buffer;
    }

    /**
    * Display page with not found code.
    */
    protected function notFound()
    {
        throw new NotFoundException();
    }

    /**
    * Load language file with defined constants.
    *
    * @param string
    * @param string
    * @return array
    */
    protected function language($lang, $file = 'default')
    {
        return APP.'Languages/'.$lang.'/'.$file.'.php';
    }

    /**
    * Load model.
    *
    * @param string
    * @return object
    */
    protected function model($model)
    {
        $model = MODELS."\\".$model;
        return new $model();
    }

    /**
    * Redirect helper function.
    *
    * @var string
    * @var int 
    */
    protected function redirect($url = '', $statusCode = 303)
    {
        header('Location: '.\Core\Util\Util::base($url), true, $statusCode);
        die();
    }

    /**
    * Redirect helper function.
    *
    * @var string
    * @var int 
    */
    protected function redirectToUrl($url = '', $statusCode = 303)
    {
        header('Location: '.$url, true, $statusCode);
        die();
    }
}