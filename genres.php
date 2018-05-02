<?php
include_once(__DIR__ . '/genremap.php');
$genreList = __DIR__ . '/genre.list';

if ($_POST['submit'] == 'true') {
    foreach ($_POST as $key => $value) {
        if (in_array($value, $types)) {
            $output[] = str_replace('_', ' ', $key) . ':' . $genreMap[$value];
        }
    }
    $output = implode("\n", $output);
    file_put_contents($genreList, $output);
} else if (file_exists($genreList)) {
    $output = trim(file_get_contents($genreList));
} else {
    $output = '';
}

$output = explode("\n", $output);
foreach ($output as $line) {
    $map = array_map('strrev', explode(':', strrev($line), 2));
    $outputmap[str_replace(' ', '_', $map[1])] = $map[0];
}

$output = $outputmap;
?>
<html>
<head>
<title>Category Defintions</title>

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
</head>
<body>

<form method="post">
<div class="container">
<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">Channel</th>
      <th scope="col">Genre</th>
    </tr>
  </thead>
  <tbody>
<?php
$includeList = explode("\n", trim(file_get_contents(__DIR__ . '/include.list')));
foreach ($includeList as $channel) {
?>
    <tr>
        <td><?php echo $channel ?></th>
        <td>
            <select class="form-control" name="<?php echo str_replace(' ', '_', $channel); ?>">
                <option>---</option>
                <?php
                foreach ($types as $type) {
                ?>
                <option
                <?php
                    if ($output[str_replace(' ', '_', $channel)] == $genreMap[$type]) {
                ?>
                    selected 
                <?php
                }
                ?>
                ><?php echo $type ?></option>
                <?php
                }
                ?>
            </select>
        </td>
    </tr>
<?php
}
?>
  </tbody>
</table>
<input name="submit" type="hidden" value="true">
<button type="submit" class="btn btn-primary mb-2">Update</button>

</div>
</form>

</body>
</html>
