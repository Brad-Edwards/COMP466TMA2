# COMP466TMA2

Purpose:

This solution was for assignment 2 of Athabasca University's COMP466 - Advanced Web Technologies course.

Observations:

1. If I had taken then time up front to use TDD, debugging would have taken a lot less time afterwards. However, learning some unit testing frameworks for JavaScript and PHP while doing the assignment would have been a whole other thing that I frankly did not have time for.

2. I have not done a lot of work in JavaScript and PHP. I am sure it shows. There is a lot I would want to revisit; naming conventions, project layout, modularity, and more. Also, I would have used JQuery, Bootstrap and others if it was permitted. Unfortunately, we were supposed to write the code ourselves so I did not import them. They are on my list.

3. Context switching between languages has costs. I am sure those costs will fall as I get more experience with JavaScript and PHP, but it added some noticeable latency to my work.

4. Sublime Text 3 was so nice to work in after VS 2019. I like VS 2019, but the typing lag, file opening lag, lag on the lag, they all slow things down. The features are ridiculously powerful, but for tiny projects like this one it is just not worth the weight. I will have to try VS Code though, since everyone seems to be raving about it.

5. Did I mention frameworks and libraries? Doing these projects this way when I know JQuery, Node.JS, AngularJS, React and all the rest are out there was a little painful. I understand the purpose of the exercise, but from what I read JavaScript development is so entwined with frameworks and libraries that I think leaving them out may do more harm than good.

Assignment 2:

Part 1

For this project, you will be using MySQL and PHP to develop a web application that provides an online bookmarking service to users on the Internet. The requirements are as follows:

The web application should have a good thoughtful interface, with menus and/or navigation buttons.
It should have a name or logo shown across all the pages.
It should begin with a welcome or greeting message and a list of ten most popular websites that users of the web application have bookmarked.
Once signed in, a list of bookmarks the user has made should be displayed, and the user can browse any web site in the list in a new browser tab or window by clicking a link to the site.
The user can add new websites to the list and edit and delete any of the existing ones in the list;
When adding or editing, user input needs to be validated using JavaScript, to make sure the URLs are not only correct but also active.

Part 2

In this part of the assignment, you are required to develop a small-scale online learning management system that can be used to deliver online courses to learners.

To that end, you will have to think about what these online courses are, how they can be developed and how they should be stored on the web server, how they can be retrieved from the server, how they should be delivered to a web browser, and then rendered/presented properly on the web browser.

The development of online courses is the collaboration of efforts of subject matter experts (SMEs) and experts in computing and web technology. It is very common that those SMEs do not know much about computing, and don’t know how to use HTML and other web technologies needed to present an attractive course, but they should be quite comfortable with languages and terms used in education. So, the first technical step you need to take is to design a SME-friendly language for marking up educational materials, EML in our term (just another XML like you created for marking up your resume), for the SMEs to use. The EML you design may be a comprehensive one for marking up the contents for an entire course, or several XML languages in small scale for different components of a course. For example, you may have an EML for a lesson/lecture delivered in just a teaching/learning session, an EML for marking up quizzes, and an EML for marking up assignments, etc. You may look at some existing languages by searching for educational markup language in Google or other search engine.

After SMEs (for this assignment, you will be the SME) have written the course contents in your EML(s), the documents must be stored on the server before they can be delivered to the learners on the web. You may think that you can save each of the documents written in your EML as a file, like the resume file you wrote for assignment 1, but this is not practical. Files may work when there is just one or only a few learning documents, but when there are many, as happens in practical situations, the documents become unmanageable. That’s why we need a database.

The next technical step is to design the database table or tables. Over the years, I have seen different designs from students in the course. The most simple and straightforward one is to have a single table. In the table each row contains information for a lesson or quiz, including the actual content written in your EML. To make the learning contents retrievable and manageable, you will need fields to identify the course and the unit the lesson or quiz belongs to. You may also keep the details of the courses and units in the same table for the purpose of this assignment project, though you wouldn’t do that in practice because it is really inefficient; in a real world situation separate tables would be used to store information about courses and units.

To deliver the course contents (lessons and quizzes in our context) in an LMS, you will need a user management module to allow learners to register and get an account in the system, like you have done in part 1 of this assignment, and then allow registered learners to login. A user who has logged into the system can then view a list of lessons available to select and a list of selected lessons and start or continue to study a selected lesson.

To present a lesson to a learner, the backend of your LMS will need to navigate through the database to find the right lesson or quiz that the learner has requested, and them retrieve the actual content in EML. For the content to be properly rendered or presented on learner’s browser, you need to translate the content in your EML to HTML. We call this process parsing. Instead of using XSLT as you did for the first assignment, parsing EML to HTML needs to be done with PHP on the server-side, which is the last important technical step for this project – to write a parser in PHP.
