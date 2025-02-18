<div class="table-responsive">
	<table class="table align-middle table-bordered table-rounded table-striped border">
		<thead>
			<tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200" style="background-color: #008B8B">
				<th style="text-align: center; color: white;">MENU</th>
				<th style="text-align: center; color: white;">AKSES</th>
				<th style="text-align: center; color: white;">ADD</th>
				<th style="text-align: center; color: white;">EDIT</th>
				<th style="text-align: center; color: white;">DELETE</th>
				<th style="text-align: center; color: white;">EXPORT</th>
				<th style="text-align: center; color: white;">IMPORT</th>
			</tr>
		</thead>
		<tbody>
            <input type="hidden" name="group_id" value="<?= $group_id; ?>"/>
            <?php
                foreach($lv1 as $row1){ ?>
                    <tr>
                        <td><b><?= html_escape($row1->label) ?></b></td>
                        <td style="text-align: center;">
                            <input type="checkbox" class="form-check-input" name="<?= html_escape($row1->id) ?>" onchange="changeSwitch(this)" <?php echo html_escape($row1->active) == "1" ? "checked" : ""; ?>>
                        </td>
                        <td style="text-align: center;">
                            <input type="checkbox" class="form-check-input" name="<?= html_escape($row1->id) ?>[FiturAdd]" <?php echo html_escape($row1->FiturAdd) == "1" ? "checked" : ""; ?>>
                        </td>
                        <td style="text-align: center;">
                            <input type="checkbox" class="form-check-input" name="<?= html_escape($row1->id) ?>[FiturEdit]" <?php echo html_escape($row1->FiturEdit) == "1" ? "checked" : ""; ?>>
                        </td>
                        <td style="text-align: center;">
                            <input type="checkbox" class="form-check-input" name="<?= html_escape($row1->id) ?>[FiturDelete]" <?php echo html_escape($row1->FiturDelete) == "1" ? "checked" : ""; ?>>
                        </td>
                        <td style="text-align: center;">
                            <input type="checkbox" class="form-check-input" name="<?= html_escape($row1->id) ?>[FiturExport]" <?php echo html_escape($row1->FiturExport) == "1" ? "checked" : ""; ?>>
                        </td>
                        <td style="text-align: center;">
                            <input type="checkbox" class="form-check-input" name="<?= html_escape($row1->id) ?>[FiturImport]" <?php echo html_escape($row1->FiturImport) == "1" ? "checked" : ""; ?>>
                        </td>
                    </tr>
                    <?php foreach ($lv2 as $row2) {
                        if(html_escape($row1->id) === html_escape($row2->header)) { ?>
                            <tr>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&#x2022;&nbsp;&nbsp;<?= html_escape($row2->label) ?></td>
                                <td style="text-align: center;">
                                    <input type="checkbox" class="form-check-input" name="<?= html_escape($row2->id) ?>" onchange="changeSwitch(this)" <?php echo html_escape($row2->active) == "1" ? "checked" : ""; ?>>
                                </td>
                                <td style="text-align: center;">
                                    <input type="checkbox" class="form-check-input" name="<?= html_escape($row2->id) ?>[FiturAdd]" <?php echo html_escape($row2->FiturAdd) == "1" ? "checked" : ""; ?>>
                                </td>
                                <td style="text-align: center;">
                                    <input type="checkbox" class="form-check-input" name="<?= html_escape($row2->id) ?>[FiturEdit]" <?php echo html_escape($row2->FiturEdit) == "1" ? "checked" : ""; ?>>
                                </td>
                                <td style="text-align: center;">
                                    <input type="checkbox" class="form-check-input" name="<?= html_escape($row2->id) ?>[FiturDelete]" <?php echo html_escape($row2->FiturDelete) == "1" ? "checked" : ""; ?>>
                                </td>
                                <td style="text-align: center;">
                                    <input type="checkbox" class="form-check-input" name="<?= html_escape($row2->id) ?>[FiturExport]" <?php echo html_escape($row2->FiturExport) == "1" ? "checked" : ""; ?>>
                                </td>
                                <td style="text-align: center;">
                                    <input type="checkbox" class="form-check-input" name="<?= html_escape($row2->id) ?>[FiturImport]" <?php echo html_escape($row2->FiturImport) == "1" ? "checked" : ""; ?>>
                                </td>
                            </tr>
                            <?php foreach ($lv3 as $row3) {
                                if(html_escape($row2->id) === html_escape($row3->header)) { ?>
                                    <tr>
                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2022;&nbsp;&nbsp;<?= html_escape($row3->label) ?></td>
                                        <td style="text-align: center;">
                                            <input type="checkbox" class="form-check-input" name="<?= html_escape($row3->id) ?>" onchange="changeSwitch(this)" <?php echo html_escape($row3->active) == "1" ? "checked" : ""; ?>>
                                        </td>
                                        <td style="text-align: center;">
                                            <input type="checkbox" class="form-check-input" name="<?= html_escape($row3->id) ?>[FiturAdd]" <?php echo html_escape($row3->FiturAdd) == "1" ? "checked" : ""; ?>>
                                        </td>
                                        <td style="text-align: center;">
                                            <input type="checkbox" class="form-check-input" name="<?= html_escape($row3->id) ?>[FiturEdit]" <?php echo html_escape($row3->FiturEdit) == "1" ? "checked" : ""; ?>>
                                        </td>
                                        <td style="text-align: center;">
                                            <input type="checkbox" class="form-check-input" name="<?= html_escape($row3->id) ?>[FiturDelete]" <?php echo html_escape($row3->FiturDelete) == "1" ? "checked" : ""; ?>>
                                        </td>
                                        <td style="text-align: center;">
                                            <input type="checkbox" class="form-check-input" name="<?= html_escape($row3->id) ?>[FiturExport]" <?php echo html_escape($row3->FiturExport) == "1" ? "checked" : ""; ?>>
                                        </td>
                                        <td style="text-align: center;">
                                            <input type="checkbox" class="form-check-input" name="<?= html_escape($row3->id) ?>[FiturImport]" <?php echo html_escape($row3->FiturImport) == "1" ? "checked" : ""; ?>>
                                        </td>
                                    </tr>
            <?php               }
                            }
                        }
                    }
                }
            ?>
		</tbody>
	</table>
</div>

<script>
    function changeSwitch(data){
        var name = data.name;
        var res = $('input[name="' + name + '"]').is(':checked');
        $('input[name="' + name + '[FiturAdd]"]').prop('checked', res);
        $('input[name="' + name + '[FiturEdit]"]').prop('checked', res);
        $('input[name="' + name + '[FiturDelete]"]').prop('checked', res);
        $('input[name="' + name + '[FiturExport]"]').prop('checked', res);
        $('input[name="' + name + '[FiturImport]"]').prop('checked', res);
    }
</script>