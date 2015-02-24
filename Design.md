# Teacher Engagement Report

_* These proposals are not exclusive to each other_

## Proposal 1

Proposal 1 is a detailed report with a column for every day. Primary activities in need of teacher attention will be listed according to the most neglected items. Different types of items also have different priorities. For example, not replying to a forum post for 2 days could be worse than not grading an assignment after 4 days.

### Implementation Details

Activity types are _not hard coded_ and can be added and removed in a modular fashion.

Activity meta types: __Action, Reaction__
  
* __Action__ is either optional, or necessary at a certain interval.

  _Example: at least every 3 days a teacher should view the course participation report for each course._

* __Reaction__ becomes necessary after an external action.

  _Example: a student submits an assignment and the teacher should grade it._

### Possible Design Decisions

#### Scope

1. Reports on Teacher progress
  1. For the Distance Learning Department
  2. For teachers themselves
 
2. Reports on Student progress
  1. For teachers
  2. For students themselves

Possible Scope Decisions include:

* Design only teacher reports with no plan for student reports
* Design only teacher reports with plans for _future_ student reports
* Design both teacher and student reports right away

Teacher and Student reports will differ only by configured activity types 

#### How will individual courses be represented?

Possible options include: 

* Make each row a course with teachers' names displayed next to each respective course
* Make each row a teacher and combine the items from all of their courses
* Make each row a teacher and combine course items but show a separation

## Proposal 2

Proposal 2 is a time chart similar to [the one Github has](https://github.com/arubaruba)

### Possible Ways to Structure the Data Include

* By Course and Unique Teacher (If a course has multiple teachers it will appear multiple times)
* By Teacher (No way of discriminating between courses)
