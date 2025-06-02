<?php
echo "We talk about variables".'<br>';
$name = 'Hoang';
$age = 18;
echo $age;
$has_mercedes = false;
var_dump($has_mercedes);
$product_price = 22.45;
var_dump($product_price);
echo '<br>';
echo $name.' is '.$age. ' years old'.'br';
echo '<br>';

$age = 15;
if ($age >= 18) {
        echo "you are greater than or euqal to 18 years old";
} elseif ($age >= 16) {
        echo "you are greater than 16 years old";
} else {
        echo "you are less than 16 years old";
}

echo "<br>";

$person = [
    'full_name' => 'Nguyen Hoang Tung',
    'age' => 43,
    'email' => 'nguyentung0910@gmail.com',
];
echo $person['full_name'];
echo '<br>';
print_r($person);
echo '<br>';
$person = [[
    'full_name' => 'Nguyen Hoang Tung',
    'age' => 43,
    'email' => 'nguyentung0910@gmail.com',
],
[
    'full_name' => 'Nguyen Hoang Tung',
    'age' => 43,
    'email' => 'nguyentung0910@gmail.com',
]]
;
print_r($person[0]);

$comments = [
    'Good', 'I like it', 'How are you?'
];
if(!empty($comments)) { 
    echo "There are some commets";
} else {
    echo 'No comments';
}

echo '<br>';

$first_comment = $comments[0] ??'No comments';
echo $first_comment;

echo '<br>';

echo 'We talke about Iterations(loop)';
for ($i = 0; $i < 10; $i++) {
    echo '<br>';
    echo " i = $i";
};

$i = 0;
while ($i < 20) {
    echo "<br>";
    echo "i = $i";
    $i = $i + 1;
};

$i = 0;
do {
    echo "<br>";
    echo "i = $i";
    $i = $i + 1;
}while($i < 30);

$fruits = ['apple','pineapple','orange','lemon'];
for($i=0;$i<count($fruits);$i++) {
    echo "$fruits[$i] <br>"; 
};
foreach($fruits as $fruit) {
    echo "$fruit <br>";
}
foreach($person as $key ==> $value) {
    
?>