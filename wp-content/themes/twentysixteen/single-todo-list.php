<?php
// Start the loop.
while ( have_posts() ) : the_post();?>

<!doctype html>
<html lang="en" data-framework="jquery">
  <head>
    <meta charset="utf-8">
    <title>jQuery • TodoMVC</title>
    <link rel="stylesheet" href="/todo/node_modules/todomvc-common/base.css">
    <link rel="stylesheet" href="/todo/node_modules/todomvc-app-css/index.css">
    <link rel="stylesheet" href="/todo/css/app.css">
  </head>
  <body data-nonce="<?php echo wp_create_nonce( 'wp_rest' ); ?>">
    <section id="todoapp">
      <header id="header">
        <h1>todos</h1>
        <input id="new-todo" placeholder="What needs to be done?" autofocus>
      </header>
      <section id="main">
        <input id="toggle-all" type="checkbox">
        <label for="toggle-all">Mark all as complete</label>
        <ul id="todo-list"></ul>
      </section>
      <footer id="footer"></footer>
    </section>
    <footer id="info">
      <p>Double-click to edit a todo</p>
      <p>Created by <a href="http://sindresorhus.com">Sindre Sorhus</a></p>
      <p>Part of <a href="http://todomvc.com">TodoMVC</a></p>
    </footer>
    <script id="todo-template" type="text/x-handlebars-template">
      {{#this}}
      <li {{#if completed}}class="completed"{{/if}} data-id="{{id}}">
        <div class="view">
          <input class="toggle" type="checkbox" {{#if completed}}checked{{/if}}>
          <label>{{title}}</label>
          <button class="destroy"></button>
        </div>
        <input class="edit" value="{{title}}">
      </li>
    {{/this}}
    </script>
    <script id="footer-template" type="text/x-handlebars-template">
      <span id="todo-count"><strong>{{activeTodoCount}}</strong> {{activeTodoWord}} left</span>
      <ul id="filters">
        <li>
          <a {{#eq filter 'all'}}class="selected"{{/eq}} href="#/all">All</a>
        </li>
        <li>
          <a {{#eq filter 'active'}}class="selected"{{/eq}}href="#/active">Active</a>
        </li>
        <li>
          <a {{#eq filter 'completed'}}class="selected"{{/eq}}href="#/completed">Completed</a>
        </li>
      </ul>
      {{#if completedTodos}}<button id="clear-completed">Clear completed</button>{{/if}}
    </script>
    <script src="/todo/node_modules/todomvc-common/base.js"></script>
    <script src="/todo/node_modules/jquery/dist/jquery.js"></script>
    <script src="/todo/node_modules/handlebars/dist/handlebars.js"></script>
    <script src="/todo/node_modules/director/build/director.js"></script>
    <script src="/todo/js/app.js"></script>
  </body>
</html>


<?php endwhile; ?>