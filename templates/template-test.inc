/** This is a test comment */
<h2>Template Header</h2>
{headerStuff}

{loop} // Entry data will be processed here

  This is content that should be displayed.
  {test}
  <ul>
  {% for element in array %}
    <li>
      {element}
    </li>
  {% endfor %}
  </ul>

{/loop}

/** This is another block comment */
Template footer.
{footerStuff}
