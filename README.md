# Mikko test

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
