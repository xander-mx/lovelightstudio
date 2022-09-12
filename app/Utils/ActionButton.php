<?php


namespace App\Utils;

/**
 * Class ActionButton, utilizada para generar botones de datatable desde el controlador
 * @package App\Utils
 */
class ActionButton
{
  //Atributo HREF del elemento <a>
  private $href;
  //Propiedad para concatenar clases css al elemento <a>
  private $classButton;
  //Array donde se almacena las propiedades attr
  private $attributes = array();
  //Icono que mostrara el botón
  private $icon;
  //Indica si muestra un spinner al dar click al botón
  private $spinner;
  // Id que tendra el elemento
  private $buttonId;
  // Function a ejecutarse al evento click
  private $click;
  //Texto a mostrar en evento hover
  private $toolTip;
  //Texto que contendra el boton
  private $label;

  /**
   * ActionButton constructor.
   */
  public function __construct()
  {
    $this->addClassButton('btn btn-info btn-xs ');
    $this->setIcon('bi ');
    $this->setSpinner(false);
  }

  public function getHref()
  {
    return $this->href;
  }

  public function setHref($href)
  {
    $this->href = $href;
  }

  public function getClassButton()
  {
    return $this->classButton;
  }

  public function addClassButton($classButton)
  {
    $this->classButton .= $classButton;
  }

  public function getAttributes(): array
  {
    return $this->attributes;
  }

  public function removeClassButton($classButton)
  {
    $this->classButton = str_replace($classButton,'',$this->classButton);
  }

  /**
   * Agrega los atributos que tendra el botón a la variable array
   * @param $key   string Id del atributo
   * @param $value string Valor del atributo
   */
  public function addAttribute(string $key, string $value)
  {
    array_push($this->attributes, $key."='".$value."' ");
  }

  public function getIcon()
  {
    return $this->icon;
  }

  public function setIcon($icon)
  {
    $this->icon .= $icon;
  }

  public function getSpinner()
  {
    return $this->spinner;
  }

  public function setSpinner($spinner)
  {
    $this->spinner = $spinner;
  }

  public function getButtonId()
  {
    return $this->buttonId;
  }

  public function setButtonId($buttonId)
  {
    $this->buttonId = $buttonId;
  }

  public function getClick()
  {
    return $this->click;
  }

  public function setClick($click)
  {
    $this->click = $click;
  }

  public function getToolTip()
  {
    return $this->toolTip;
  }

  public function setToolTip($toolTip)
  {
    $this->toolTip = $toolTip;
  }

  public function getLabel()
  {
    return $this->label;
  }

  public function setLabel($label): void
  {
    $this->label = $label;
  }

  /**
   * Devuelve el botón construido con las configuraciones asignadas, lo deveulve como string
   * @return string
   */
  public function getActionButton() {
    $button = "<a ";
    $button .= filled($this->getHref()) ? "href='". $this->getHref() ."'" : " ";
    $button .= filled($this->getToolTip()) ? " title='". $this->getToolTip() ."'" : " " ;
    $button .= filled($this->getButtonId()) ? "id='". $this->getButtonId() ."'" : " " ;
    $button .= filled($this->getClick()) ? "onclick='". $this->getClick() ."'" : " " ;
    $button .= "class='".$this->getClassButton()."'";

    foreach ($this->attributes as $attribute)
    {
      $button .= $attribute;
    }

    $button .= " style='margin-bottom: 0;'>";
    $button .= "<i class=' ". $this->getIcon() ."'></i>";

    if($this->getSpinner())
    {
      $button .= "<div class='loader' style='display: none'><div class='loader-icon-xs'></div></div></a><div class='form-group hidden-sm hidden-md hidden-lg'></div>";
    }else{
      $button .= "</a>";
    }
    return $button;
  }

  public function getRowButton() {
    $button = "<a ";
    $button .= filled($this->getHref()) ? "href='". $this->getHref() ."'" : " ";
    $button .= filled($this->getToolTip()) ? " title='". $this->getToolTip() ."'" : " " ;
    $button .= filled($this->getButtonId()) ? "id='". $this->getButtonId() ."'" : " " ;
    $button .= filled($this->getClick()) ? "onclick='". $this->getClick() ."'" : " " ;
    $button .= "class='".$this->getClassButton()."'";
    foreach ($this->attributes as $attribute)
    {
      $button .= $attribute;
    }
    $button .= " style='margin-bottom: 0;'>";
    $button .= filled($this->getLabel()) ? $this->getLabel() : "";

    $button .= "</a>";
    return $button;
  }

  public function getIconButton() {
    $button = "<button type='button' ";
    $button .= filled($this->getToolTip()) ? " title='". $this->getToolTip() ."'" : " " ;
    $button .= filled($this->getClick()) ? "onclick='". $this->getClick() ."'" : " " ;
    $button .= "class='".$this->getClassButton()."'";
    foreach ($this->attributes as $attribute)
    {
      $button .= $attribute;
    }

    $button .= ">";
    $button .= "<i class=' ". $this->getIcon() ."'></i>";
    $button .= "</button>";
    return $button;
  }

  public static function getDefaultEditButton(): ActionButton
  {
    return self::getDefaultButton("bi-pencil-square", "btn-warning", "Editar");
  }

  public static function getDefaultDeleteButton(): ActionButton
  {
    return self::getDefaultButton("bi-trash", "btn-danger", "Eliminar");
  }

  public static function getDefaultButton(string $icon, string $class, string $tooltip): ActionButton
  {
    $button = new ActionButton();
    $button->setIcon($icon);
    $button->removeClassButton('btn-info');
    $button->removeClassButton('btn-xs');
    $button->addClassButton($class);
    $button->setToolTip($tooltip);
    return $button;
  }

}