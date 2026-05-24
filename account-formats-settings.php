<?php
/**
 * Account Formats Settings Page
 * Manage account format options dynamically from WordPress admin dashboard.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ─── Default formats (used on first install) ─────────────────────────────────
function bcmarket_get_default_account_formats() {
    return array(
        array( 'value' => 'email',        'label' => 'Email ID' ),
        array( 'value' => 'mailpassword', 'label' => 'Mail Password' ),
        array( 'value' => 'alt_email',    'label' => 'Recovery email' ),
        array( 'value' => 'username',     'label' => 'Username' ),
        array( 'value' => 'userid',       'label' => 'User ID' ),
        array( 'value' => 'name',         'label' => 'Name' ),
        array( 'value' => 'login',        'label' => 'Login ID' ),
        array( 'value' => 'password',     'label' => 'Password' ),
        array( 'value' => 'gauth',        'label' => '2fa' ),
        array( 'value' => 'dob',          'label' => 'Date of Birth' ),
        array( 'value' => 'cookies',      'label' => 'Cookies' ),
        array( 'value' => 'useragent',    'label' => 'User Agent' ),
        array( 'value' => 'token',        'label' => 'Token' ),
        array( 'value' => 'phone',        'label' => 'Phone number' ),
        array( 'value' => 'gender',       'label' => 'Gender' ),
        array( 'value' => 'profile_link', 'label' => 'Profile Link' ),
        array( 'value' => 'uid',          'label' => 'UID' ),
        array( 'value' => 'backup_code',  'label' => 'Backup Code' ),
        array( 'value' => 'code_1',       'label' => 'Code 1' ),
        array( 'value' => 'code_2',       'label' => 'Code 2' ),
        array( 'value' => 'code_3',       'label' => 'Code 3' ),
        array( 'value' => 'code_4',       'label' => 'Code 4' ),
        array( 'value' => 'code_5',       'label' => 'Code 5' ),
    );
}

// ─── Get formats from DB (fallback to defaults) ───────────────────────────────
function bcmarket_get_account_formats() {
    $formats = get_option( 'bcmarket_account_formats' );
    if ( empty( $formats ) || ! is_array( $formats ) ) {
        $formats = bcmarket_get_default_account_formats();
        update_option( 'bcmarket_account_formats', $formats );
    }
    return $formats;
}

// ─── Handle save / delete actions ────────────────────────────────────────────
function bcmarket_handle_account_formats_actions() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // Save / Update formats
    if (
        isset( $_POST['bcmarket_save_formats'] ) &&
        check_admin_referer( 'bcmarket_account_formats_nonce', 'bcmarket_nonce_field' )
    ) {
        $values = isset( $_POST['format_value'] ) ? (array) $_POST['format_value'] : array();
        $labels = isset( $_POST['format_label'] ) ? (array) $_POST['format_label'] : array();

        $new_formats = array();
        foreach ( $values as $i => $val ) {
            $val   = sanitize_key( $val );
            $label = isset( $labels[ $i ] ) ? sanitize_text_field( $labels[ $i ] ) : '';
            if ( ! empty( $val ) && ! empty( $label ) ) {
                $new_formats[] = array( 'value' => $val, 'label' => $label );
            }
        }

        update_option( 'bcmarket_account_formats', $new_formats );
        add_action( 'admin_notices', function () {
            echo '<div class="notice notice-success is-dismissible"><p>✅ Account formats saved successfully!</p></div>';
        } );
    }

    // Reset to defaults
    if (
        isset( $_POST['bcmarket_reset_formats'] ) &&
        check_admin_referer( 'bcmarket_account_formats_nonce', 'bcmarket_nonce_field' )
    ) {
        delete_option( 'bcmarket_account_formats' );
        add_action( 'admin_notices', function () {
            echo '<div class="notice notice-warning is-dismissible"><p>⚠️ Account formats reset to defaults!</p></div>';
        } );
    }
}
add_action( 'admin_init', 'bcmarket_handle_account_formats_actions' );

// ─── Render the settings page ─────────────────────────────────────────────────
function bcmarket_account_formats_page_content() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have sufficient permissions.' ) );
    }

    $formats = bcmarket_get_account_formats();
    ?>
    <div class="wrap" id="bcmarket-formats-wrap">
        <h1 style="display:flex;align-items:center;gap:10px;">
            <span class="dashicons dashicons-list-view" style="font-size:28px;width:28px;height:28px;color:#2271b1;"></span>
            Account Format Manager
        </h1>
        <p class="description" style="font-size:14px;margin-bottom:20px;">
            Manage the account format fields that partners see when uploading accounts. Add, reorder, rename, or remove formats dynamically.
        </p>

        <form method="post" id="bcmarket-formats-form">
            <?php wp_nonce_field( 'bcmarket_account_formats_nonce', 'bcmarket_nonce_field' ); ?>

            <div style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:24px;max-width:860px;">

                <!-- Table header -->
                <div style="display:grid;grid-template-columns:40px 1fr 1fr 60px;gap:10px;font-weight:600;color:#555;border-bottom:2px solid #e0e0e0;padding-bottom:10px;margin-bottom:10px;">
                    <span style="text-align:center;">#</span>
                    <span>Format Value <small style="font-weight:400;color:#888;">(used in code)</small></span>
                    <span>Display Label <small style="font-weight:400;color:#888;">(shown to partner)</small></span>
                    <span style="text-align:center;">Remove</span>
                </div>

                <!-- Format rows -->
                <div id="format-rows-container">
                    <?php foreach ( $formats as $index => $fmt ) : ?>
                    <div class="format-row" style="display:grid;grid-template-columns:40px 1fr 1fr 60px;gap:10px;align-items:center;margin-bottom:8px;padding:8px 0;border-bottom:1px solid #f0f0f0;">
                        <span class="row-index" style="text-align:center;color:#aaa;font-size:13px;"><?php echo $index + 1; ?></span>
                        <input
                            type="text"
                            name="format_value[]"
                            value="<?php echo esc_attr( $fmt['value'] ); ?>"
                            placeholder="e.g. email"
                            class="regular-text"
                            style="font-family:monospace;font-size:13px;"
                        />
                        <input
                            type="text"
                            name="format_label[]"
                            value="<?php echo esc_attr( $fmt['label'] ); ?>"
                            placeholder="e.g. Email ID"
                            class="regular-text"
                            style="font-size:13px;"
                        />
                        <button type="button" class="remove-row-btn" title="Remove this format"
                            style="background:#fff;border:1px solid #d63638;color:#d63638;border-radius:4px;padding:4px 8px;cursor:pointer;font-size:18px;line-height:1;display:flex;align-items:center;justify-content:center;">
                            &times;
                        </button>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Add new row button -->
                <div style="margin-top:16px;">
                    <button type="button" id="add-format-row-btn"
                        style="display:inline-flex;align-items:center;gap:6px;background:#2271b1;color:#fff;border:none;border-radius:5px;padding:8px 16px;cursor:pointer;font-size:14px;">
                        <span class="dashicons dashicons-plus-alt2" style="font-size:18px;width:18px;height:18px;"></span>
                        Add New Format
                    </button>
                </div>
            </div>

            <!-- Action buttons -->
            <div style="margin-top:20px;display:flex;gap:12px;flex-wrap:wrap;">
                <button type="submit" name="bcmarket_save_formats"
                    style="background:#2271b1;color:#fff;border:none;border-radius:5px;padding:10px 24px;font-size:15px;cursor:pointer;display:inline-flex;align-items:center;gap:6px;">
                    <span class="dashicons dashicons-saved" style="font-size:18px;width:18px;height:18px;"></span>
                    Save Formats
                </button>
                <button type="submit" name="bcmarket_reset_formats"
                    onclick="return confirm('Are you sure you want to reset to defaults? All custom formats will be lost.');"
                    style="background:#f6f7f7;color:#2c3338;border:1px solid #c3c4c7;border-radius:5px;padding:10px 24px;font-size:15px;cursor:pointer;display:inline-flex;align-items:center;gap:6px;">
                    <span class="dashicons dashicons-image-rotate" style="font-size:18px;width:18px;height:18px;"></span>
                    Reset to Defaults
                </button>
            </div>

            <p style="margin-top:12px;color:#888;font-size:12px;">
                💡 <strong>Tip:</strong> Format Value must be unique and use only lowercase letters, numbers, and underscores. The Display Label is what partners see in the dropdown.
            </p>
        </form>
    </div>

    <script>
    (function($){
        var rowCount = <?php echo count( $formats ); ?>;

        // Add new row
        $('#add-format-row-btn').on('click', function(){
            rowCount++;
            var row = `
                <div class="format-row" style="display:grid;grid-template-columns:40px 1fr 1fr 60px;gap:10px;align-items:center;margin-bottom:8px;padding:8px 0;border-bottom:1px solid #f0f0f0;">
                    <span class="row-index" style="text-align:center;color:#aaa;font-size:13px;">${rowCount}</span>
                    <input type="text" name="format_value[]" value="" placeholder="e.g. my_field" class="regular-text" style="font-family:monospace;font-size:13px;" />
                    <input type="text" name="format_label[]" value="" placeholder="e.g. My Field" class="regular-text" style="font-size:13px;" />
                    <button type="button" class="remove-row-btn" title="Remove this format"
                        style="background:#fff;border:1px solid #d63638;color:#d63638;border-radius:4px;padding:4px 8px;cursor:pointer;font-size:18px;line-height:1;display:flex;align-items:center;justify-content:center;">
                        &times;
                    </button>
                </div>`;
            $('#format-rows-container').append(row);
            reIndexRows();
        });

        // Remove row
        $(document).on('click', '.remove-row-btn', function(){
            $(this).closest('.format-row').remove();
            reIndexRows();
        });

        // Re-number rows
        function reIndexRows(){
            $('#format-rows-container .format-row').each(function(i){
                $(this).find('.row-index').text(i + 1);
            });
        }

    })(jQuery);
    </script>
    <?php
}
