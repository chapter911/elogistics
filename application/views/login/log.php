<style>
.embed-container {
    position: relative;
    padding-bottom: 80%;
    height: 0;
    max-width: 100%;
}

.embed-container iframe,
.embed-container object,
.embed-container iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

small {
    position: absolute;
    z-index: 40;
    bottom: 0;
    margin-bottom: -15px;
}
</style>
<div class="embed-container"><iframe width="500" height="400" frameborder="0" scrolling="no" marginheight="0"
        marginwidth="0" title="map_indonesia"
        src="//gis.pln.co.id/arcgis/apps/Embed/index.html?webmap=00931c3d454d4d72898f6c77bd5d7a44&extent=89.248,-17.8478,143.2129,9.4548&zoom=true&previewImage=false&scale=true&search=true&searchextent=true&details=true&disable_scroll=true&theme=light"></iframe>
</div>

<div class="card mb-6">
    <div class="card-header header-elements">
        <h3 class="mb-0 me-2">Daftar Login</h3>
    </div>
    <div class="collapse show">
        <div class="row">
            <div class="card-datatable p-10 text-nowrap">
                <table id="mytable" class="table">
                    <thead>
                        <tr style="background-color: #008B8B">
                            <th style="text-align: center; color: white">NO</th>
                            <th style="text-align: center; color: white">USERNAME</th>
                            <th style="text-align: center; color: white">LOGIN DATE</th>
                            <th style="text-align: center; color: white">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($log as $d) { ?>
                        <tr>
                            <td style="text-align: center;"><?= $i . "."; ?></td>
                            <td style="text-align: center;"><?= html_escape($d->username); ?></td>
                            <td style="text-align: center;"><?= html_escape($d->login_date); ?></td>
                            <td style="text-align: center;">
                                <?= html_escape($d->is_logged_in) == 1 ? "<span class='badge bg-primary'>success</span>" : "<span class='badge bg-danger'>failed</span>" ;?>
                            </td>
                        </tr>
                        <?php
                        $i++;
                    } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
var table;

$(document).ready(function() {
    table = $("#mytable").DataTable({
        "scrollX": true,
        "fixedHeader": {
            "header": true,
            "headerOffset": $('.layout-navbar').height() + 15
        },
        "pageLength": 10,
    });
});
</script>