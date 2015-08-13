<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Templating engine 1.0
 * developed for LameWork
 * by Milos Zarkovic mzarkovicm@gmail.com
 */
class Template
{
  /**
   * Stores the location of the template file
   * @var string
   */
  public $template_file = "none";

  /**
   * Stores the contents of the template file
   * @var string
   */
  private $_template;

  public function generate_template( $template_data = array() )
  {
    $this->_load_template();
    return $this->_parse_template( $template_data );
  }

  /**
   * Loads a template file with which markup should be formatted
   * @return string The contents of the template file
   */
  private function _load_template()
  {
    // Check for a custom template
    $template_file = BASEPATH . '/templates/' . $this->template_file;
    if( file_exists($template_file) && is_readable($template_file) )
    {
      $path = $template_file;
    }

    // Look for a system template
    else if( file_exists($default_file = BASEPATH . '/templates/default.inc') && is_readable($default_file) )
    {
      $path = $default_file;
    }

    // If the default template is missing, throw an error
    else
    {
      throw new Exception( 'No default template found: '.$default_file );
    }
    // Load the contents of the file and return them
    $this->_template = file_get_contents($path);
  }

  /**
   * Make the thing run
   * @param  array  $template_data data providetd for the template tags
   */
  private function _parse_template( $template_data = array() )
  {
    foreach ( $template_data as $var_name => $value )
    {
      //var_dump($template_data);
      // If $value is an array,
      // look for it's loop in the template
      if (is_array($value))
      {
        //var_dump("An array!");
        $for_start_pattern = "#{% for (.*?) in ".$var_name." %}#";
        $inside_for = "#".$var_name." %}\s*(.*?)\s*{% endfor %}#is";
        // Look for loops
        if (!preg_match($for_start_pattern, $this->_template, $match))
         throw new Exception( 'Template is not set for provided data.' );
        // Get templates inside the loops
        preg_match($inside_for, $this->_template, $loop);
        $singular = $match[1];
        $loop_pattern = $loop[1];
        $loop_content = "";
        foreach ( $value as $entry )
        {
          // Reset replace_value
          $replace_value = "";
          // Add loop pattern to loop content
          $loop_content .= $loop_pattern;
          //Check if entry is an object
          if ( is_object($entry) )
          {
            // Pattern for the object property
            $pattern = "#.*{".$singular."->(.*?)}.*#is";
            // Get the number of distinct tags
            preg_match_all("#{".$singular."->(.*?)}#", $loop_pattern, $matches);
            $num_of_tags = count(array_unique($matches[0]));

            for ($i=1; $i <= $num_of_tags ; $i++)
            {
              // Get the property name we are asking for
              // in the template.
              $property = preg_replace($pattern, "$1", $loop_content);
              //var_dump($property);
              $replace_pattern = "/{(".$singular."->".$property.")}/";

              if ( property_exists( $entry, $property ) )
              {
                // Get the object property
                $replace_value = $entry->$property;
              }
              else
              {
                // Return the original tag from the template
                $replace_value = "Not set: ".$singular."->".$property;
              }

              $loop_content = preg_replace($replace_pattern, $replace_value, $loop_content);
            }
          }
          else
          {
            //var_dump("not object: ",$entry);
            $replace_pattern = "/{(".$singular.")}/";
            $replace_value = $entry;
            $loop_content = preg_replace($replace_pattern, $replace_value, $loop_content);
          }

        }
        $replace_loop_pattern = "#{% for ".$singular." in ".$var_name." %}(.*?){% endfor %}#is";
        $this->_template = preg_replace($replace_loop_pattern, $loop_content, $this->_template);

      }
      elseif (is_object($value))
      {
        //var_dump("An object!");
        // Pattern for object property
        $pattern = "#.*{".$var_name."->(.*?)}.*#is";
        // Get the number of distinct tags
        preg_match_all("#{".$var_name."->(.*?)}#", $this->_template, $matches);
        $num_of_tags = count(array_unique($matches[0]));

        for ($i=1; $i <= $num_of_tags ; $i++)
        {
          //get wanted property
          $property = preg_replace($pattern, "$1", $this->_template);
          $replace_pattern = "/{(".$var_name."->".$property.")}/";
          $this->_template = preg_replace($replace_pattern, $value->$property, $this->_template);
        }
      }
      else
      {
        //var_dump("A string!");
        $pattern = "/{(".$var_name.")}/";
        $this->_template = preg_replace($pattern, $value, $this->_template);
      }
    }

    echo $this->_template;
  }

}
