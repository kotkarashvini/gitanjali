<?php

class ErrorController extends AppController {

    public function exception_occurred($ErrorCode = NULL) {
        $this->layout = 'error';
        if ($ErrorCode == '23503') {
            $this->Session->setFlash(
                    __('Record canot be deleted : Foreign key violation')
            );
            return $this->redirect($this->request->referer());
        } else {
          /* $this->Session->setFlash(
                   __('Something Went wrong!')
           );*/
            //session_destroy();
        }
    }

    public function csrftoken() {

        $this->layout = 'error';
        //  session_destroy();
    }

    public function notfound() {
        $this->layout = 'error';
        //  session_destroy();
    }

    public function unauthenticate() {

        $this->layout = 'error';
        // session_destroy();
    }

}
