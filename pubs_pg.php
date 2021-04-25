<?php
global $wpdb;
$table_name = "ncart_pubs";
$sql=$wpdb->get_results("SELECT * FROM $table_name ORDER BY year DESC, ID DESC");

if (isset($_REQUEST["filter"])) {
$yr=$_POST["yr"];
$project=$wpdb->_real_escape(iconv("UTF-8","ASCII//TRANSLIT", $_POST["project"]));
$keyword=$wpdb->_real_escape(iconv("UTF-8","ASCII//TRANSLIT", $_POST["keyword"]));

if (!empty($project) AND empty($yr) AND empty($keyword)) {
$sql=$wpdb->get_results("SELECT * FROM $table_name WHERE project_url='$project' ORDER BY year DESC, ID DESC");
}
if (empty($project) AND !empty($yr) AND empty($keyword)) {
$sql=$wpdb->get_results("SELECT * FROM $table_name WHERE year=$yr ORDER BY ID DESC");
}
if (empty($project) AND empty($yr) AND !empty($keyword)) {
$sql=$wpdb->get_results("SELECT * FROM $table_name WHERE title LIKE '%$keyword%' OR abstract LIKE '%$keyword%' OR authors LIKE '%$keyword%' OR abstract LIKE '%$keyword%' OR tags LIKE '%$keyword%' OR publisher LIKE '%$keyword%' OR country LIKE '%$keyword%' OR city LIKE '%$keyword%' ORDER BY year DESC, ID DESC");
}
if (!empty($project) AND !empty($yr) AND empty($keyword)) {
$sql=$wpdb->get_results("SELECT * FROM $table_name WHERE project_url='$project' AND year=$yr ORDER BY ID DESC");
}
if (!empty($project) AND empty($yr) AND !empty($keyword)) {
$sql=$wpdb->get_results("SELECT * FROM $table_name WHERE project_url='$project' AND (title LIKE '%$keyword%' OR abstract LIKE '%$keyword%' OR authors LIKE '%$keyword%' OR abstract LIKE '%$keyword%' OR tags LIKE '%$keyword%' OR publisher LIKE '%$keyword%' OR country LIKE '%$keyword%' OR city LIKE '%$keyword%') ORDER BY year DESC, ID DESC");
}
if (empty($project) AND !empty($yr) AND !empty($keyword)) {
$sql=$wpdb->get_results("SELECT * FROM $table_name WHERE year=$yr AND (title LIKE '%$keyword%' OR abstract LIKE '%$keyword%' OR authors LIKE '%$keyword%' OR abstract LIKE '%$keyword%' OR tags LIKE '%$keyword%' OR publisher LIKE '%$keyword%' OR country LIKE '%$keyword%' OR city LIKE '%$keyword%') ORDER BY ID DESC");
}
if (!empty($project) AND !empty($yr) AND !empty($keyword)) {
$sql=$wpdb->get_results("SELECT * FROM $table_name WHERE project_url='$project' AND year=$yr AND (title LIKE '%$keyword%' OR abstract LIKE '%$keyword%' OR authors LIKE '%$keyword%' OR abstract LIKE '%$keyword%' OR tags LIKE '%$keyword%' OR publisher LIKE '%$keyword%' OR country LIKE '%$keyword%' OR city LIKE '%$keyword%') ORDER BY ID DESC");
}
unset($_POST["filter"]);
}
?>

<style>

<?php foreach ($sql as $row) {
$id="row".strval($row->ID);
$id_details="details".strval($row->ID);

$extra=substr($row->abstract, 200);
?>

#<?php echo $id; ?>:hover {
background-color:azure;
}

details {
cursor:pointer;
}
details > summary {
max-width:105ch;

}

details summary#<?php echo $id_details; ?>:after {
  content:"...Read More";
  color:#0000EE;
}
details[open] > summary#<?php echo $id_details; ?>:after {
  content:"<?php echo $wpdb->_real_escape(iconv("UTF-8","ASCII//TRANSLIT", $extra)); ?>";
  color:#444545;
}

<?php } ?>

.links {
display:inline-block;
float:right;
text-align:right;
border:solid 1px;
border-radius:5px;
padding:2px;
}
.meida{
display:inline;
float:right;
text-align:right;
}
</style>
<form action="" method="POST">
Filter By: <select style="max-width:82px;" name="project">
<option value="">Project</option>
<option value="http://ncart.scs.ryerson.ca/research/access-hole-detection/">Access Hole Detection</option>
<option value="http://ncart.scs.ryerson.ca/research/canine-assisted-robot-deployment-card/">Canine Assisted Robot Deployment (CARD)</option>
<option value="http://ncart.scs.ryerson.ca/research/canine-augmentation-technology-cat-for-usar/">Canine Augmentation Technology (CAT) for USAR</option>
<option value="http://ncart.scs.ryerson.ca/research/ongoing-archaeological-exploration-project-in-el-hibeh/">Ongoing Archaeological Exploration Project in El-Hibeh</option>
<option value="http://ncart.scs.ryerson.ca/research/robotics-education-at-computer-science-department/">Robotics Education at Computer Science Department</option>
<option value="http://ncart.scs.ryerson.ca/research/usar-robots-in-action-disaster-city-texas-april-06/">USAR Robots in Action (Disaster City, Texas, April 06)</option>
<option value="http://ncart.scs.ryerson.ca/research/3d-scene-reconstructions-of-disaster-environment/">3D Scene Reconstructions of Disaster Environment</option>
</select>
<select name="yr">
<option value="">Year</option>
<?php
$max=$wpdb->get_results("SELECT MAX(year) AS max FROM $table_name");
$max_yr = (int) $max[0]->max;
$min=$wpdb->get_results("SELECT MIN(year) AS min FROM $table_name");
$min_yr = (int) $min[0]->min;

for ($i=$max_yr; $i>=$min_yr; $i--) {
echo "<option value=\"$i\">$i</option>";
}
?>

</select>
<input type="text" name="keyword" placeholder="Keyword">
<input type="submit" name="filter" value="Filter">
</form>
<table>
<?php
foreach ($sql as $row) {
$id="row".strval($row->ID);
$id_details="details".strval($row->ID);
$title=$row->title;
$authors=$row->authors;
$publisher=$row->publisher;
$month=$row->month;
$year=$row->year;
$vol=$row->vol;
$issue=$row->issue;
$article=$row->article;
$pg_start=$row->pg_start;
$pg_end=$row->pg_end;
$abstract=$row->abstract;
$tags=$row->tags;
$city=$row->city;
$country=$row->country;
$project_url=$row->project_url;
$url=$row->url;
$image_url=$row->img_url;
$vid_url=$row->video_url;
$author_cit = "";
$author_list = "";

if (strpos($authors, ';')) {
$author_arr = explode(';', $authors);
}

elseif (strpos($authors, ',')) {
$author_arr = explode(',', $authors);
}
elseif (strpos($authors, 'And')) {
$author_arr = explode('And', $authors);
}

$i=0;
foreach ($author_arr as $name) {
        $name=trim($name, " ");
        $name_arr = explode(" ", $name);
        $first_char = substr($name_arr[0], 0, 1);
        $author_list .= "$first_char. $name_arr[1], ";
        
        if ($i < 3) {
        $author_cit .= "$first_char. $name_arr[1], ";
        }
        $i += 1;
}
if ($i > 2) {
        $author_cit .= "et al.";
        }

$author_cit = rtrim($author_cit, ", ");
$author_list = rtrim($author_list, ", ");
$author_cit .= ".";

$citation = "$author_cit \"$title\". <i>$publisher</i>.";

if ($vol > 0) {
$citation .= " Vol. $vol, ";
}
if ($issue > 0) {
$citation .= "No. $issue";
}
if ($article > 0) {
$citation .= "Article. $article";
}


if ($pg_start > 0 and $pg_end > 0) {
$citation .= ", $year, ";
$citation .= "pp. $pg_start-$pg_end. ";

}
else {
$citation .= " $year.";
}




$tag_line= "<h5>Tags: $tags </h5>";
$project_line="<a href=$project_url><div class=\"links\">Associated Project</div></a>";
$media_line="<div class=\"media\"><details><summary style=\"color:#000EE\">Media</summary><p>$vid_url<p></details></div>";


if (strlen($abstract)<201){
$abstract_line="<p style=\"max-width:105ch;\">Abstract: $abstract</p>";
}
else {
$abstract_show=substr($row->abstract,0 , 200);
$abstract_line="<details><summary id=$id_details>Abstract: $abstract_show</summary></details>";
}


?>
<tr><td id=<?php echo $id; ?>><div><h3><?php echo $title; ?></h3><h4>Authored by: <?php echo $author_list?></h4><?php echo $abstract_line; ?><?php if (!empty($tags)) {echo $tag_line; } ?><center><h6><?php echo $citation; ?></h6></center><a href=<?php echo $url; ?>><div class="links" style="margin-left:5px">PDF</div></a></div><?php if (!empty($project_url)){echo $project_line;}?><!--?php if (!empty($vid_url) OR !empty($img_url)){echo $media_line;}?--></td></tr>
<?php
}
?>

</table>
