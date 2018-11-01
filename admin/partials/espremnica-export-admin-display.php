<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Espremnica_Export
 * @subpackage Espremnica_Export/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<h1>eSpremnica export podatkov za etikete v CSV</h1>
<div class="espremnica">
    <table>
        <thead>
            <tr>
                <td></td>
                <td><?php _e('Številka naročila', 'espremnica_export') ?></td>
                <td><?php _e('Tip pošiljke', 'espremnica_export') ?></td>
                <td><?php _e('Teža', 'espremnica_export') ?></td>
                <td><?php _e('Znesek', 'espremnica_export') ?></td>
                <td><?php _e('Valuta', 'espremnica_export') ?></td>
                <td><?php _e('Država', 'espremnica_export') ?></td>
                <td><?php _e('ID', 'espremnica_export') ?></td>
                <td><?php _e('Po povzetju', 'espremnica_export') ?></td>
                <td><?php _e('Ime in priimek', 'espremnica_export') ?></td>
                <td><?php _e('Podjetje', 'espremnica_export') ?></td>
                <td><?php _e('Naslov', 'espremnica_export') ?></td>
                <td><?php _e('Poštna št.', 'espremnica_export') ?></td>
                <td><?php _e('Pošta', 'espremnica_export') ?></td>
                <td><?php _e('Telefon', 'espremnica_export') ?></td>
                <td><?php _e('Email', 'espremnica_export') ?></td>
            </tr>
        </thead>
        <tbody>
            <?php echo $this->espremnica_get_orders(); ?>
        </tbody>
    </table>
</div>

<?php $this->espremnica_start_download() ?>
<?php $this->espremnica_csv_upload() ?>


<form method="post" enctype="multipart/form-data" class="espremnica-csv-upload">
    <input type="file" name="csv_file" id="csv_file"  multiple="false" accept=".csv" />
    <input type="submit" value="Upload" name="submit" />
</form>




