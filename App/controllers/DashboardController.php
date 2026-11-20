<?php
class DashboardController extends BaseController {
    public function index(): void {
        $this->requireLogin();
        $model = new Dashboard();
        $this->render('dashboard/index', ['title'=>'Dashboard','summary'=>$model->summary(),'rows'=>$model->completeness()]);
    }
}
