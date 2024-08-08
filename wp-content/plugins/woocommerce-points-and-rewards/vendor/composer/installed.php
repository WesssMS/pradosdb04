<?php return array(
    'root' => array(
        'name' => 'woocommerce/woocommerce-points-and-rewards',
        'pretty_version' => 'dev-release/1.8.6',
        'version' => 'dev-release/1.8.6',
        'reference' => '1c9461086e89f5f76895f7186ff72ab0132a9342',
        'type' => 'wordpress-plugin',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => false,
    ),
    'versions' => array(
        'composer/installers' => array(
            'pretty_version' => 'v1.12.0',
            'version' => '1.12.0.0',
            'reference' => 'd20a64ed3c94748397ff5973488761b22f6d3f19',
            'type' => 'composer-plugin',
            'install_path' => __DIR__ . '/./installers',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'roundcube/plugin-installer' => array(
            'dev_requirement' => false,
            'replaced' => array(
                0 => '*',
            ),
        ),
        'shama/baton' => array(
            'dev_requirement' => false,
            'replaced' => array(
                0 => '*',
            ),
        ),
        'woocommerce/grow' => array(
            'pretty_version' => 'dev-compat-checker',
            'version' => 'dev-compat-checker',
            'reference' => '92a72d51e81b2ec62eb74d3e950a1e55cac63469',
            'type' => 'library',
            'install_path' => __DIR__ . '/../woocommerce/grow',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'woocommerce/woocommerce-points-and-rewards' => array(
            'pretty_version' => 'dev-release/1.8.6',
            'version' => 'dev-release/1.8.6',
            'reference' => '1c9461086e89f5f76895f7186ff72ab0132a9342',
            'type' => 'wordpress-plugin',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
    ),
);
