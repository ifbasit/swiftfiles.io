<?php
$sites = [
    'uofmnproperties.com' => 'https://uofmnproperties.com/logs',
    'liganois.com' => 'https://liganois.com/logs',
    'dinerwaitress.com' => 'https://dinerwaitress.com/logs',
    'gamevigor.com' => 'https://gamevigor.com/logs',
    'egyptianroyal.com' => 'https://egyptianroyal.com/logs',
    'betguardian.co.uk' => 'https://betguardian.co.uk/logs',
    'apologymag.com' => 'https://apologymag.com/logs',
    'arcanereels.com' => 'https://arcanereels.com/logs',
    'builldwln.com' => 'https://builldwln.com/logs',
    'phillytoo.com' => 'https://phillytoo.com/logs',
    'arcanereels.com' => 'https://arcanereels.com/logs',
    'jennifercollinsmd.com' => 'https://jennifercollinsmd.com/logs',
    'pchgateway.com' => 'https://pchgateway.com/logs',
    'cyberlemonade.com' => 'https://cyberlemonade.com/logs',
    'pchgateway.com' => 'https://pchgateway.com/logs',
    'lottoroos.com' => 'https://lottoroos.com/logs',
    'musicures.com' => 'https://musicures.com/logs',
    'obgynmasterluke.com' => 'https://obgynmasterluke.com/logs',


];

$selectedSite = $_GET['site'] ?? '';
$selectedLog = $_GET['log'] ?? '';

$dataRows = [];

if ($selectedSite && $selectedLog && isset($sites[$selectedSite])) {
    $url = $sites[$selectedSite] . '/' . $selectedLog;
    $json = @file_get_contents($url);
    if ($json !== false) {
        $data = json_decode($json, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
            $i = 1;
            foreach ($data as $entry) {
                $meta = $entry['meta'] ?? [];
                $dataRows[] = [
                    'index'    => $i++,
                    'ip'       => $meta['ip'] ?? '',
                    'datetime' => $meta['datetime'] ?? '',
                    'reason'   => $meta['reason'] ?? '',
                    'status'   => $meta['status'] ?? '',
                    'geo'      => $meta['geo'] ?? '',
                    'language' => $meta['language'] ?? '',
                    'referrer' => $meta['referrer'] ?? '',
                    'uniqid'   => $meta['uniqid'] ?? '',
                    'FP'       => json_encode($entry['fingerprint'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
                    'IPQS'     => json_encode($entry['ipqs'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
                ];
            }
        }
    }
}

usort($dataRows, function($a, $b) {
    return strtotime($b['datetime']) <=> strtotime($a['datetime']);
});

$i = 1;
foreach ($dataRows as &$row) {
    $row['index'] = $i++;
}
unset($row);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Log Viewer</title>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/datatables.net-colresize-unofficial@latest/jquery.dataTables.colResize.css">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Kumar+One&family=REM:ital,wght@0,100..900;1,100..900&display=swap');
:root {

  --primary-dark: #031b4d;
  --secondary-dark: #7886C7;
  --primary-light: rgb(0, 97, 235);
  
  --secondary-light: #A9B5DF;
    --color-white: #fff;
  --color-black: #333;
  --color-gray: #75787b;
  --color-gray-light: #bbb;
  --color-gray-disabled: #e8e8e8;
  --color-green: #53a318;
  --color-green-dark: #383;
  --font-size-small: .75rem;
  --font-size-default: .875rem;
}
body { background-color: #f8f9fa; margin: 0; font-family: "REM", serif; }
h2 { margin-top: 20px; }
pre { white-space: pre-wrap; word-wrap: break-word; margin: 0; }
.dataTables_filter, .dataTables_length { margin-bottom: 1rem; }
.table thead th { background-color: #343a40; color: #fff; }
.table-striped tbody tr:nth-of-type(odd) { background-color: #f2f2f2; }
.table-responsive {
  overflow-x: auto;
}

table.dataTable {
  width: 100% !important;
}
  textarea:focus, input[type="text"]:focus,
input[type="password"]:focus,
input[type="datetime"]:focus,
input[type="datetime-local"]:focus,
input[type="date"]:focus,
input[type="month"]:focus,
input[type="time"]:focus,
input[type="week"]:focus,
input[type="number"]:focus,
input[type="email"]:focus,
input[type="url"]:focus 
input[type="search"]:focus,
input[type="tel"]:focus,
input[type="color"]:focus, .uneditable-input:focus,
textarea {

    border-color: #5333ee;
    box-shadow: none;
    outline: 0 none;
}
.credit {
    margin: 10px;
    font-size: 12px;
    color: #999;
}
input[type="text"],
select, textarea  {
    font-size: 0.9375rem;

    width: auto;
    font-weight: 400;
    color: #3c4d62;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid var(--secondary-light);
    border-radius: 4px;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
input[type="text"]:focus{    
    color: #3c4d62;
    background-color: #fff;
    border-color: var(--secondary-dark);
    outline: 0;
    box-shadow: 0 0 0 3px rgba(101, 118, 255, 0.1);
}

td, label, #logTable_info, a {
    font-weight: 200 !important;
}
th {
    font-weight: 300 !important;
}
select {
    font-weight: 200 !important;
}
pre {font-weight: 200 !important;
    font-family: 'REM';}

button {
    cursor: pointer;
    width: auto;
    border: 0;
    background-color: var(--primary-light);
    border-color: var(--primary-light);
    border-radius: 4px;
    position: relative;
    letter-spacing: 0.02em;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.6875rem 1.5rem;
    font-size: 0.9375rem;
    color: #fff;
    text-align: center;
    vertical-align: middle;
    user-select: none;
    line-height: 1.25rem;
    transition: .2s ease-in-out;
}

table.dataTable tbody th, table.dataTable tbody td {
    font-size: 13px !important;
}
table.dataTable thead>tr>th.sorting, table.dataTable thead>tr>th.sorting_asc, table.dataTable thead>tr>th.sorting_desc, table.dataTable thead>tr>th.sorting_asc_disabled, table.dataTable thead>tr>th.sorting_desc_disabled, table.dataTable thead>tr>td.sorting, table.dataTable thead>tr>td.sorting_asc, table.dataTable thead>tr>td.sorting_desc, table.dataTable thead>tr>td.sorting_asc_disabled, table.dataTable thead>tr>td.sorting_desc_disabled {
        font-size: 13px !important;
}

</style>
</head>
<body>
<div class="container mb-3">
<h2 class="mb-4">Log Viewer</h2>
<form method="get" class="row g-3 mb-4">
    <div class="col-md-3">
        <select name="site" class="form-select">
            <option value="">-- Select Site --</option>
            <?php foreach ($sites as $key => $url): ?>
                <option value="<?= htmlspecialchars($key) ?>" <?= $key === $selectedSite ? 'selected' : '' ?>>
                    <?= htmlspecialchars($key) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2">
        <select name="log" class="form-select">
            <option value="">-- Select Log Type --</option>
            <option value="visit.log" <?= $selectedLog === 'visit.log' ? 'selected' : '' ?>>Visit Log</option>
            <option value="error.log" <?= $selectedLog === 'error.log' ? 'selected' : '' ?>>Error Log</option>
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Load Log</button>
    </div>
</form>
<hr>

<?php if ($dataRows): ?>

<!-- Filters -->
<div class="row g-3 mb-3">
    <div class="col-md-3">
        <select id="filter-geo" class="form-select">
            <option value="">Country</option>
            <?php foreach(array_unique(array_column($dataRows, 'geo')) as $geo): ?>
                <option value="<?= htmlspecialchars($geo) ?>"><?= htmlspecialchars($geo) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3">
        <select id="filter-reason" class="form-select">
            <option value="">Reason</option>
            <?php foreach(array_unique(array_column($dataRows, 'reason')) as $reason): ?>
                <option value="<?= htmlspecialchars($reason) ?>"><?= htmlspecialchars($reason) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3">
        <select id="filter-status" class="form-select">
            <option value="">Status</option>
            <?php foreach(array_unique(array_column($dataRows, 'status')) as $status): ?>
                <option value="<?= htmlspecialchars($status) ?>"><?= htmlspecialchars($status) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3">
        <input type="text" id="filter-datetime-range" class="form-control" placeholder="Select Date Range">
    </div>


</div>
<hr>
<div class="table-responsive">
    <table id="logTable" class="table table-responsive">
    <thead>
        <tr>
            <th>#</th>
            <th>IP</th>
            <th>Datetime</th>
            <th>Reason</th>
            <th>Status</th>
            <th>Geo</th>
            <th>Language</th>
            <th>Referrer</th>
            <th>Uniqid</th>
            <th>FP</th>
            <th>IPQS</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($dataRows as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['index']) ?></td>
                <td><?= htmlspecialchars($row['ip']) ?></td>
                <td><?= htmlspecialchars($row['datetime']) ?></td>
                <td><?= htmlspecialchars($row['reason']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td><?= htmlspecialchars($row['geo']) ?></td>
                <td><?= htmlspecialchars($row['language']) ?></td>
                <td><?= htmlspecialchars($row['referrer']) ?></td>
                <td><?= htmlspecialchars($row['uniqid']) ?></td>
                <td><pre><?= htmlspecialchars($row['FP']) ?></pre></td>
                <td><pre><?= htmlspecialchars($row['IPQS']) ?></pre></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php elseif ($selectedSite && $selectedLog): ?>
<p class="alert alert-warning">No data found or invalid JSON.</p>
<?php endif; ?>
</div>
<div class="credit">
    By Abdul Basit (ifbasit@gmail.com)
  </div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net-colresize-unofficial@latest/jquery.dataTables.colResize.js"></script>

<script>
$(document).ready(function(){

    var options = {
        isEnabled: true,
        saveState: false,
        hoverClass: 'dt-colresizable-hover',
        hasBoundCheck: true,
        minBoundClass: 'dt-colresizable-bound-min',
        maxBoundClass: 'dt-colresizable-bound-max',
        isResizable: function (column) {
            return true;
        },
        onResizeStart: function (column, columns) {
            console.log('onResizeStart');
        },
        onResize: function (column) {
            console.log('onResize');
        },
        onResizeEnd: function (column, columns) {
            console.log('onResizeEnd');
        },
        getMinWidthOf: function ($thNode) {
        },
        stateSaveCallback: function (settings, data) {
        },
        stateLoadCallback: function (settings) {
        }
    }

    var table = $('#logTable').DataTable({
    pageLength: 50,
    colResize: options
});
    $('#filter-geo').on('change', function(){
        table.column(5).search(this.value).draw();
    });
    $('#filter-reason').on('change', function(){
        table.column(3).search(this.value).draw();
    });
    $('#filter-status').on('change', function(){
        table.column(4).search(this.value).draw();
    });


flatpickr("#filter-datetime-range", {
    mode: "range",
    dateFormat: "Y-m-d",
    onChange: function(selectedDates, dateStr, instance){
        $.fn.dataTable.ext.search = $.fn.dataTable.ext.search.filter(function(fn){
            return false;
        });

        if (selectedDates.length === 2) {
            var startDate = new Date(selectedDates[0]);
            startDate.setHours(0,0,0,0);
            var startTime = startDate.getTime();

            var endDate = new Date(selectedDates[1]);
            endDate.setHours(23,59,59,999);
            var endTime = endDate.getTime();

            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var datetimeStr = data[2]; 
                    if (datetimeStr) {
                        var rowTime = new Date(datetimeStr).getTime();
                        return rowTime >= startTime && rowTime <= endTime;
                    }
                    return false;
                }
            );
        }
        table.draw();
    }
});



});
</script>

</body>
</html>
