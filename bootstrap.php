<?php

// bootstrap configuration

// initialize the template folder
Rain\Tpl::configure('tpl_dir', Config::get('app.template-path'));
Rain\Tpl::configure('tpl_ext', 'tpl');
Rain\Tpl::configure('cache_dir', Config::get('app.template-cache-path'));
