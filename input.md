Node.js is one of the emerging technologies to write real-time applications using one of your favorite web languages: **Javascript**. With the ample choices of frameworks  to write your first web server, not even a single one offers the desired developer experience. This is where AdonisJs shines.

[AdonisJs](http://adonisjs.com) is a beautifully crafted **MVC framework** for Node.js. In this article I'll show you how to get started with AdonisJs by installing the framework, discussing the features and creating a welcome page.

![](https://cdn.scotch.io/1/iIJBgpRdSMSvDcIlmLoH_uZ6NvEu.png)

<script>alert('xss')</script>

## What is AdonisJs?

> <script>alert('blockquote')</script> 

`<script>alert('code tag')</script> 
`<script>alert('code tag')</script>`

```<html>tag</html>```

[AdonisJs](http://adonisjs.com) is inspired by a Php Framework called [Laravel](http://laravel.com). It borrows the concepts of Dependency injection and service providers to write beautiful code which is testable to its core.

In the age of shiny web frameworks, AdonisJs focuses on the key aspects of creating stable and scalable web applications, some of them are:

### Developer Experience

The framework makes use of latest inbuilt ES2015 features to get rid of spaghetti code. You will hardly find yourself writing **callbacks** since there is a solid support for ES2015 generators. Also, the OOP nature of the framework helps you in abstracting your code into multiple re-usable chunks.

### Consistent API

The API throughout the code base is so consistent that after working for a while, you will be able to guess the method names, expected output, etc.

### Speed & Productivity

AdonisJs ships with a bunch of 1st party components known as providers. Writing entire web server is a matter of weeks(if not days). It ships with solid support for **Mailing**, **Authentication**, **Redis**, **SQL ORM**, **Data validation and sanitization**, etc.

## Installing AdonisJs

The installation process is straight forward. Just make sure you have got versions of `node >= 4.0` and `npm >=3.0`.

Let's start by installing `adonis-cli` globally using npm. It is a command line tool to scaffold new applications within a matter of seconds.

```bash
npm i -g adonis-cli
```

![install-cli.gif](https://s16.postimg.org/aaoetxi7p/install_cli.gif)

Now we can start by creating new projects. For this article, we will setup a project called `hello-adonis`.

```bash
adonis new hello-adonis
```

Above command will setup a new project with the pre-configured directory structure. Don't get overwhelmed by the directory structure as in next article we will talk about it.

Now `cd` into the newly configured project and executing the following command to start the HTTP server.

```bash
cd hello-adonis
npm start

# info adonis:framework +6ms serving app on localhost:3333
```

Open [http://localhost:3333](http://localhost:3333) to see the welcome page.

![](https://s17.postimg.org/jfazrfdy7/Screen_Shot_2016_09_01_at_3_40_12_PM.png)

In this article, we will discuss the framework concepts and some exciting features and in the next article, we will have fun by writing some code.

## Powerful Routing

AdonisJs has out of the box support for defining fluent routes. Setting up express style routes to CRUD resources is a just a matter of seconds.

Let's explore the framework routing layer.

```javascript
const Route = use('Route') // importing route

Route.get('/home', 'HomeController.welcome')
```

Above we defined a simple route to URL `/home`. Once the given route is called the `welcome` method on HomeController will be invoked.

Let's move to my favorite part of routing called `resources`. Resources help you in defining multiple conventional RESTful routes within a single line of code.

```javascript
const Route = use('Route') // importing route

Route.resource('users', 'UserController')
```

Above line of code will set a total of seven routes.


| Url | Verb  |  Controller Method |  Purpose |
|-----|-------|--------------------|-----------|
/users | GET  | index |  Show list of all users
/users/create  | GET | create |  Display a form to create a new user.
/users | POST  |  store  | Save user submitted via form to the database.
/users/:id | GET | show  |  Display user details using the id
/users/:id/edit | GET | edit |   Display the form to edit the user.
/users/:id | PUT/PATCH  | update | Update details for a given user with id.
/users/:id | DELETE | destroy | Delete a given user with id.

That's not all. You can also filter and extend resources based upon the nature of the application.

```javascript
const Route = use('Route') // importing route

Route
    .resource('users', 'UserController')
    .only(['index', 'store', 'update', 'destroy'])
    .addMember('profile')
```

Whoooo! As I said, the fluent method chaining makes it so simple to filter the resources down to only four routes, and also with the help of `addMember`, we can add a new route to the `users` resource.

Here's a neat video to show off some more routing features:

https://www.youtube.com/watch?v=w7LD7E53w3w

### Route Middleware

Before we move onto the next feature, let's see how to work with route middleware.

```javascript
Route
    .get('/account', 'UserController.account')
    .middleware('auth')
```

Middleware(s) are defined by chaining the `middleware` method, and you are free to specify one or more middleware by passing multiple arguments.

## Active Record ORM

If you ever worked with Rails or Laravel, you will feel right at home. AdonisJs ORM called **Lucid** is a robust implementation of Active Record. It supports all popular SQL databases like **MYSQL**, **Oracle**, **PostgreSQL**, **SQLite**, etc. Also, the database engine has support for data models, fluent query builder, and migrations.

Let's take a quick look at how to define and interact with SQL data models also known as **Lucid models**. 

```javascript
const Lucid = use('Lucid')

class User extends Lucid {

    posts () {
        return this.hasMany('App/Model/Post')
    }

}
```

Now you can use the Post Model as follows.

```javascript
const User = use('App/Model/User') // require user model

yield User.all() // get all users
const loue = yield User.findBy('username', 'Loue') // find by username

yield loue.posts().fetch() // fetch all posts by loue
```


## Ace Commands

There's hardly any Node.js framework which gives you the power to write project-specific terminal commands with the ease as similar to AdonisJs. AdonisJs has a beautiful tool called `Ace`, which makes it super easy and intuitive to write your terminal commands. Don't believe me! Let's check it out.

Commands are stored in the `app/Commands` directory, and each file represents a single command. Let's play with the pre-existing `Greet` command.

Run the following command from the root of your project.

```bash
./ace greet "Your name."
```

![](https://s21.postimg.org/akrilv8nr/ace_greet.gif)

It is the simplest command you can ever write, but that does not limit you from creating more useful commands. For now, we will discuss the basic structure of a command class. Whereas you must reference the [offical documentation](http://adonisjs.com/docs/interactive-shell).

```javascript
const Command = use('Command')

class Greet extends Command {
    
    get signature () {
        return 'greet {name}'
    }

    get description () {
        return 'Greet a user with their name.'
    }

    * handle (args, options) {
        // called when command is executed
    }

}
```

1. Command `signature` defines the command name and the required/optional arguments to be passed when running the command.
2. The description gets displayed on the help screen.
3. `handle` is an ES2015 generator method, which is called when your command is invoked. You are free to do almost anything inside this method.

## Features At A Glance

There is a lot more to cover when it comes to AdonisJs features. Let's sum them within a small list to keep this first article simple and easy to digest.

1. Multi-transport mailer.
2. Robust middleware layer to interact with incoming HTTP requests.
3. Nunjucks based templates.
4. Support for emitting and listening to application-wide events.
5. Inbuilt support for Redis.
6. Secure and straightforward file uploads.
7. Security features with support for **CORS**, **CSRF**, **Content sniffing** and **XSS** attacks.
8. Supportive and friendly community.

## Future

AdonisJs is under active development with the latest release of version **3.0**. Major API specs have finalized so that upcoming releases will have less or no breaking changes. 

Also, there is a complete roadmap on [Trello](https://trello.com/b/yzpqCgdl/adonis-for-humans) sharing what's next in the queue. To summarize, you will soon get support for:

1. Social Authentication via Facebook, Google, Github, etc.
2. Complete testing framework.
3. Support for caching database queries and views fragments.
4. Inbuilt support for posting messages to Slack.

## Coming up Next!

This post is an introduction to [AdonisJs](http://adonisjs.com), walking through some of the fundamentals and key features of the framework. In the upcoming post, we will together write a simple link sharing website like [EchoJs](http://www.echojs.com/).