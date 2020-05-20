
<?php
session_start();
require_once 'config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';

$search_str = filter_input(INPUT_GET, 'search_str');
$pagelimit = 15;

$page = filter_input(INPUT_GET, 'page');
if (!$page) {
    $page = 1;
}

$db = getDbInstance();

$select = array('SeqNo', 'Name');

if ($search_str) {
    $db->where('Name', '%' . $search_str . '%', 'like');
}

$db->pageLimit = $pagelimit;

$rows = $db->arraybuilder()->paginate('eventwallgroups', $page, $select);

$total_pages = $db->totalPages;
?>

<?php include BASE_PATH . '/includes/header.php'; ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-6">
            <h1 class="page-header">Event wall</h1>
        </div>
    </div>
    <?php include BASE_PATH . '/includes/flash_messages.php'; ?>

    <div class="well text-center filter-form">
        <form class="form form-inline" action="">
            <label for="input_search">Search</label>
            <input type="text" class="form-control" id="input_search" name="search_str" value="<?php echo htmlspecialchars($search_str, ENT_QUOTES, 'UTF-8'); ?>">
            <input type="submit" value="Go" class="btn btn-primary">
        </form>
    </div>
    <hr>
    <table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th>ID</th>
                <th>Eventwall name</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): ?>
            <tr>
            <td><?php echo $row['SeqNo']; ?></td>
            <td><form action="event_channels10.php?SeqNo=<?php echo $row['SeqNo']; ?>" method="POST">

                <select id="config" name="config">
                    <option value="4">2*2</option>
                    <option value="9">3*3</option>
                    <option value="16">4*4</option>
                    <option value="25">5*5</option>
                    <option value="36">6*6</option>
                </select>
                <input type="submit" value="<?php echo $row['Name']; ?>">
            </td></form>              
            </tr>            
            <?php endforeach; ?>
        </tbody>
    </table>      
</div> 
<?php include BASE_PATH . '/includes/footer.php'; ?>
