# FullStack PHP Programmer Task Solutions

## Backend programming

### Task 1: Class test1
1. Static function `last_word($sentence)` — returns the length of the last word in the sentence or 0 for an empty string.
2. Static function `extract_string($str)` — returns the part of the string enclosed in square brackets. For example, for the string "The quick [brown fox].", it returns "brown fox".

**Solution**:
[See solution in the file](./backend/Test1.php)

<details>
  <summary>Show the code</summary>

```php
class Test1
{
    public static function last_word($sentence)
    {
        $sentence = trim($sentence);
        if (empty($sentence)) {
            return 0;
        }

        preg_match('/\b[\w-]+\b(?=[^\w-]*$)/', $sentence, $matches);
        return !empty($matches[0]) ? strlen($matches[0]) : 0;
    }

    public static function extract_string($str)
    {
        $str = trim($str);
        if (empty($str)) {
            return '';
        }

        preg_match('/\[(.*?)\]/', $str, $matches);
        return $matches[1] ?? '';
    }

    public static function extract_all_string($str)
    {
        $str = trim($str);
        if (empty($str)) {
            return [];
        }

        preg_match_all('/\[(.*?)\]/', $str, $matches);
        return $matches[1] ?? [];
    }
}

echo Test1::last_word('Hello World!!!') . PHP_EOL;
echo Test1::extract_string('Hello [World]') . PHP_EOL;
print_r(Test1::extract_all_string('[Hello] [World]'));
```
</details>

### Task 2: Class test2
1. Function `is_power($number, $base)` — returns true if there exists a number n such that $base^n = $number. Example: is_power(16, 4) = true.
2. Function `format_number($str)` — returns a string with all non-numeric characters removed except for commas and periods. Example: "$4,000.15A" will return "4,000.15".
3. Function `sum_digits($int)` — returns the sum of the digits of the given number. Example: sum_digits(9123) = 15.

**Solution**:
[See full solution in the file](./backend/Test2.php)

<details>
  <summary>Show the code</summary>

```php
class Test2
{
    public function is_power($number, $base)
    {
        if (!is_numeric($number) || !is_numeric($base)) {
            return false;
        }

        if ($number == 1) {
            return true;
        }

        if ($base == $number) {
            return true;
        }

        if ($base == 0 || abs($base) == 1) {
            return $number == $base;
        }

        if ($number == 0) {
            return false;
        }


        $power = log(abs($number)) / log(abs($base));

        if ($power == (int)$power) {
            if ($power % 2 == 0) {
                if ($number > 0) {
                    return true;
                }
            } else {
                if ($number < 0 && $base < 0 || $number > 0 && $base > 0) {
                    return true;
                }
            }
        }

        return false;
    }

    public function format_number($str)
    {
        return preg_replace('/[^\d.,]/', '', $str);
    }

    public function sum_digits($int)
    {
        return array_sum(str_split(abs($int)));
    }

}

$obj = new Test2();

var_dump($obj->is_power(16, 4));
var_dump($obj->is_power(12, 3));
var_dump($obj->is_power(1, 100));
var_dump($obj->is_power(-8, -2));
var_dump($obj->is_power(8, -2));
var_dump($obj->is_power(-8, 2));
var_dump($obj->is_power(-4, 2));
var_dump($obj->is_power(4, -2));
var_dump($obj->is_power(-4, -2));
var_dump($obj->is_power(1, 1));
var_dump($obj->is_power(-1, 1));
var_dump($obj->is_power(1, -1));
var_dump($obj->is_power(-1, -1));
var_dump($obj->is_power(6.25, -2.5));

echo $obj->format_number("$4,000.15A") . PHP_EOL;

echo $obj->sum_digits(12345) . PHP_EOL;
echo $obj->sum_digits(-12345) . PHP_EOL;
```
</details>

### Task 3: Fixing code for fetching coordinates
Given code fetches x, y coordinates of multiple locations marked by numeric ID. Identify issues and suggest improvements.

**Solution**:
[See full solution in the file](./backend/task3.php)

<details>
  <summary>Show the code</summary>

```php
//original code
$data = [];
foreach ($ids as $id) {
    $result = $connection->query("SELECT `x`, `y` FROM `values` WHERE `id` = " . $id);
    $data[] = $result->fetch_row();
}

// For the start, I would rewrite it with PDO for future interaction
// It will be enough to replace ->fetch_row() with ->fetch() in this example of code

// The problem is in the multiple connections to the database, as we create new connection each time
// For solution we can use 'IN' instead of 'foreach'
$ids = implode(',', $ids);
$data[] = $connection->query("SELECT `x`, `y` FROM `values` WHERE `id` IN (" . $ids . ")")->fetch_all();

// Also the problem is possible SQL injections, so it would be better to use prepared queries
// Of course would be easier to do it with only one query
// prepare the query
$stmt = $connection->prepare("SELECT `x`, `y` FROM `values` WHERE `id` = :id");
$stmt->execute([':id' => $id]);
$data[] = $stmt->fetch();
// But in this case we have already chosen to use IN instead of foreach
$idsList = implode(',', array_fill(0, count($ids), '?'));

$stmt = $connection->prepare("SELECT `x`, `y` FROM `values` WHERE `id` IN ($idsList)");
$stmt->execute($ids);

$data = $stmt->fetchAll();

// Anyway it would be better to add a check
if (!empty($ids)) {
}
// I would write some features like logging PDO errors, so I will know where is the problem
// Also we can add PDO::FETCH_NUM to fix the format of response

// If we expect to work with big data I would request data with chunks
// for example
$chunkSize = 1000;
$chunks = array_chunk($ids, $chunkSize);
foreach ($chunks as $chunk) {
    // previous code with request preparation
}
```
</details>

### Task 4: MySQL Query
What will be the result of the following query:
```sql
SELECT a.id, a.name, b.grade
FROM a
LEFT JOIN b on b.id = a.id;
```

The tables 'a' and 'b' are as follows:

#### Table 'a'
| Id  | Name  |
| --- | ----- |
| 1   | Eli   |
| 2   | Moshe |
| 3   | Yossi |

#### Table 'b'
| Id  | Grade |
| --- | ----- |
| 1   | 98    |
| 3   | 55    |
| 4   | 100   |

Options:
- 3 rows, 1 NULL value
- 2 rows, no NULL values
- 3 rows, 2 NULL values
- 9 rows, no NULL values

**Solution**:  
The answer is **"3 rows, 1 NULL value"**. It was easy to choose because we used a LEFT JOIN, which returns NULL for non-matching values like `a.id = 2`

### Task 5: MySQL queries with salesman and customer tables
1. Write an SQL statement to get the names of all customers, their cities, and salesmen's names, where the salesman's commission is between 12% and 14%.
2. Write an SQL statement to get all salesmen who didn’t sell anything.

**Solution**:
1. SQL statement:
```sql
select c.Name as customer_name, c.city, s.Name as salesman_name
from customer c
         join salesman s on c.Salesman_id = s.Id
where s.commission between 0.12 and 0.14;
```
2. SQL statement:
```sql
select s.*
from salesman s
         left join customer c on s.Id = c.Salesman_id
where c.Salesman_id is null;
```

### Task 6: MySQL INSERT queries
Which of the following queries will succeed, and which will fail? Explain your reasoning.
1. a `INSERT INTO example (id, text2) VALUES(1, 'test');`
2. b `INSERT INTO example (text1, id) VALUES('test', 1);`
3. c `INSERT INTO example (text1, text2) VALUES('test', 'test');`
4. d `INSERT INTO example (id, text1, text2) VALUES(1, 'test', 'test');`

**Solution**:  
a. Fail - text1 field has NOT NULL it means we must provide a value for it.  
b. Success - all required fields are provided (field text2 also NOT NULL but has default value) so in this example we can use any order for fields.  
c. Fail - because in this example id which is also NOT NULL is not provided. The table was created incorrectly because id field must be auto_increment and there is no primary key for id.  
d. Success - all fields provided.

### Task 7: Removing duplicates from an array
Write a function that removes duplicates from an array and returns an array of only unique elements.

**Solution**:
```js
function unique(arr) {
    return Array.from(new Set(arr));
}
```
I have recently re-read js documentation, so we just use Set which is array with unique values, simply put

### Task 8: Array duplication
Write a function that duplicates an array a given number of times. For example:
```javascript
duplicate([1, 2, 3, 4, 5], 3);
// Result: [1,2,3,4,5,1,2,3,4,5,1,2,3,4,5]
```

**Solution**:
```js
function duplicate(arr, times) {
  let duplicated = [];
  for (let i = 0; i < times; i++) {
    duplicated = [...result, ...arr];
  }
  return duplicated;
}
```
I use this syntax because I am used to write "..." In PHP when I work with arrays


### Task 9: Click handler issue
Why doesn’t clicking on the dynamically added button trigger any action? How can this be fixed?

**Solution**:
[See full solution in the file](./frontend/task9.js)

<details>
  <summary>Show the code</summary>

```js
// original code
$("button.clickable").on("click", function () {
    console.log("Button Clicked:", this);
}); // defines the click handler for all buttons
$.get({...}).success(function (res) {
    $("body").append("<button id=`btn_${res.id}` class="clickable">Click Alert!</button>");
}; // dynamically add another button to the page

// first of all, this code cannot work correctly because it has syntax errors:
// incorrect use of quotes that breaks the string, and ` should be used for the entire string, not just for inserting a variable
 $("body").append(`<button id="btn_${res.id}" class="clickable">Click Alert!</button>`);
// also, this code is missing a closing ) for the success() function

// to solve the event handling problem, there are several solutions:
// 1. assign a handler to the parent element instead of directly targeting the element,
// which the script cannot find because it was added after the script was initialized
$("body").on("click", "button.clickable", function() {
    console.log("Button Clicked:", this);
});

// 2. I wouldn't use this option, but you can add an onclick attribute in the HTML element
<button id="btn_${res.id}" class="clickable" onclick="btnClick">Click Alert!</button>
// and add the function in JS
function btnClick() {
    console.log("Button Clicked:", this);
}

// 3. you can first create the element and then add an event handler to it (i would use this variant)
const button = $("<button>", {
    id: `btn_${res.id}`,
    class: "clickable",
    text: "Click Alert!"
});

button.on("click", function() {
    console.log("Button Clicked:", this);
});

$("body").append(button);
```
</details>

### Task 10: Incrementing badge number on button click
Using jQuery, write code to increase the number inside the badge by 1 each time the button is clicked. Bonus: make the code support multiple notification buttons.

**Solution**:
[See full solution in the file](./frontend/task10.js)

<details>
  <summary>Show the code</summary>

```js
$(document).ready(function () {
    $('.btn .badge').each(function () {
        $(this).text((Math.random() * 100).toFixed(0));
    });

    $('.btn').click(function () {
        let badge = $(this).find('.badge');
        badge.text(+badge.text() + 1);
    });
});
```
</details>

### Full-Stack Task
Design a page using jQuery, Ajax, Bootstrap, and DataTables that allows users to input a username and a number to calculate the Fibonacci sequence. It should also show the history of all user inputs in a paginated table.

**Solution**:
[To see the solution for this task visit another repository](https://github.com/defproger/Fibonacci-Calculator)
