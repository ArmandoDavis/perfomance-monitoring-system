<?php
/** admin dashboard */
Breadcrumbs::for('admin_panel.dashboard', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Dashboard'), route('admin_panel.dashboard'));
});


/** departments */
Breadcrumbs::for('admin_panel.departments.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Departments'), route('admin_panel.departments.index'));
});

Breadcrumbs::for('admin_panel.departments.create',function($breadcrumbs){
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Departments list' ), route('admin_panel.departments.index'));
    $breadcrumbs->push(__('Create' ), route('admin_panel.departments.create'));
});

Breadcrumbs::for('admin_panel.departments.edit', function($breadcrumbs, $department){
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Departments list' ), route('admin_panel.departments.index'));
    $breadcrumbs->push(__('Edit'), route('admin_panel.departments.edit', $department));
});
