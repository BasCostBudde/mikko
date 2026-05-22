# Mikko test

## Installation
- checkout this repository
- run `composer install`
- run the app via the command line using
  php main.php [output-filename]

## Requirements
The assignment document states this:

You are required to create a small application to help a fictional company determine the dates
they need to pay salaries to their sales department. This company is handling their sales payroll
in the following way:
* Sales staff get a regular monthly fixed base salary and a monthly bonus.
* The base salaries are paid on the last day of the month unless that day is a Saturday or a
Sunday (weekend).
* On the 15th of every month bonuses are paid for the previous month, unless that day is a
weekend. In that case, they are paid the first Wednesday after the 15th.
The output of the application should be a CSV file, containing the payment dates for the
remainder of this year. The CSV file should contain a column for the month name, a column that contains the salary payment date for that month, and a column that contains the bonus payment
date.

Although the flowchart states the application reads the output filename (CSV) from CLI arguments
which may assume a CLI application. A CLI application is not required. The application can be for
example web-based and provide access to a CSV download. As a candidate you are free to do
whatever suits you best.

## Approach

This project runs php 7.4

### Proof of Concept
First I create a minimal-effort script to see what the output could look like. 

poc.php runs from the command line, takes one parameter as a filename and tries to write its output to that file name.
- if there is no parameter, the script assumes that "out.csv" was meant
- if a file cannot be created at the location, the script tells you and exits with status 1
- if a file with that name already exists, the script tells you and exits with status 1

For this proof of concept there is already a swathe of assumptions.
- date format in the output. I assume yyyy-mm-dd.
- locale to use. I assume system.
- whether to include the bonus date if the current date is past the 15th of the month. I assume yes.
- csv parameters to use (separator, quotes, escaping). I assume system.
- whether to include a headers row. I assume no.

I found the second requirement to be incomplete: if the last day of the month is a weekend day, no action is specified.
 While I believe most companies would choose the *next* work day, I choose the day *before* that weekend.

### Testable design
Already in creating the simplest script variant I noticed that testing its functionality is painful. Remove the output file, run the script, inspect the file, wading through characters and comparing to an actual calendar.

These requirements specify that a file with certain contents be written. The only explicit input to the application is the destination file name; the current date is an implicit input.
Testing this application addresses two concerns of it:
- does the application create a file?
- does the created file have the desired contents?
The first concern is an integration issue. The application talks to a file system, which is a boundary. I decide to create an interface for the file system, suited to this application, with a concrete adapter as an implementation. I do not see how to test this adapter, so I postpone this test.

interface FileSystem
public function exists($filename): bool;
 # return true if filename exists
public function creatable($filename): bool;
 # return true if filename can be created and written to
public function write($filename, $contents): bool;
 # return false if something went wrong during writing ("disk full")

The second concern is business logic. The algorithm takes inputs and creates output, which is purely functional. This is easy to test drive.
The logic does not depend on the file system adapter or vice versa, so I see little value in testing their interaction.

In order for the business logic to be reliably testable, however, the implicit input needs to go. The application will provide a starting date to the logic. Let's call it PaySchedule

class PaySchedule
public function get($date): array;

Furthermore, the contents of the file are required to be csv. The schedule is just an array. Formatting the array into csv is a separate concern, to be handled by the Formatter interface

interface Formatter
public function format(array $input): string;

with a concrete implementation CsvFormatter.

Then there is the aspect of the output file name parameter. I will bring this into the application via a Parameter interface

interface Parameter
public function get($name): ?string;

with a TerminalParameter implementation, later to be extended to also provide command line options (like "-y 2024" to make the algorithm start in 2024, or "-f" to overwrite an existing target file instead of refusing operation)

These objects can be test driven. The application bundles them into something that works:

$app = new App(new ProductionFileSystem(), new TerminalParameter(), new CsvFormatter(), new PaySchedule());
$today = date();
$app->process($today);
