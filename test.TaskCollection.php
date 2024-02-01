<?php
require_once('class.collection.php');
abstract class AbstractTask {
    private $title;
    private $date;
    private $dueDate;
    private $assignedTo;
    private $description;

    public function setTitle($title){
        $this->title = $title;
    }

    public function getTitle(){
        return $this->title;
    }

    public function setDate($date){
        $this->date = $date;
    }

    public function getDate(){
        return $this->date;
    }

    public function setDueDate($dueDate){
        $this->dueDate = $dueDate;
    }

    public function getDueDate(){
        return $this->dueDate;
    }

    public function setAssignedTo($assignedTo){
        $this->assignedTo = $assignedTo;
    }

    public function getAssignedTo(){
        return $this->assignedTo;
    }

    public function setDescription($description){
        $this->description = $description;
    }

    public function getDescription(){
        echo "- Tarea: " . $this->getTitle();
        echo "<ul>";
        echo "<li>Fecha: " . $this->getDate() . "</li>";
        echo "<li>Fecha entrega: " . $this->getDueDate() . "</li>";
        echo "<li>Asignado: " . $this->getAssignedTo() . "</li>";
        echo "<li>Descripción: " . $this->description . "</li>";
    }
}

class TaskCollection extends Collection {
    public function addTask(AbstractTask $task = null, $key = null) {
        $this->addItem($task, $key);
    }
}

class Project extends AbstractTask {
    private $budget;
    public $Workitems;

    function __construct($title, $date, $dueDate, $assignedTo, $description, $budget){
        parent::setTitle($title);
        parent::setDate($date);
        parent::setDueDate($dueDate);
        parent::setAssignedTo($assignedTo);
        parent::setDescription($description);
        $this->setBudget($budget);
        $this->Workitems = new TaskCollection();
    }

    public function add(AbstractTask $Workitem){
        $this->Workitems->addTask($Workitem);
    }

    public function remove(AbstractTask $Workitem){
        $this->Workitems->removeItem($Workitem);
    }

    public function setBudget($budget){
        $this->budget = $budget;
    }

    public function getBudget(){
        return $this->budget;
    }

    public function hasChildren() {
        return $this->Workitems->length() > 0;
    }

    public function getChild($i) {
        return $this->Workitems->getItem($i);
    }

    public function getDescription(){
        parent::getDescription();
        echo "<li>Presupuesto: " . $this->getBudget() . "</li>";
        if ($this->hasChildren()){
            echo "<ul> Workitems: </br>";
            for ($i = 0; $i < $this->Workitems->length(); $i++) {
                $this->Workitems->getItem($i)->getDescription();
            }
            echo "</ul>";
        } else {
            echo "</ul>";
        }
    }
}

class TimeBasedTask extends AbstractTask {
    private $estimatedHours;
    private $hoursSpent;
    private $ChildTasks;

    public function __construct($title, $date, $dueDate, $assignedTo, $description, $hoursEstimated, $hoursSpent){
        parent::setTitle($title);
        parent::setDate($date);
        parent::setDueDate($dueDate);
        parent::setAssignedTo($assignedTo);
        parent::setDescription($description);
        $this->setEstimatedHours($hoursEstimated);
        $this->setHoursSpent($hoursSpent);
        $this->ChildTasks = new TaskCollection();
    }

    public function add(AbstractTask $childTask){
        $this->ChildTasks->addTask($childTask);
    }

    public function remove(AbstractTask $childTask){
        $this->ChildTasks->removeItem($childTask);
    }

    public function setEstimatedHours($estimatedHours){
        $this->estimatedHours = $estimatedHours;
    }

    public function getEstimatedHours(){
        return $this->estimatedHours;
    }

    public function setHoursSpent($hoursSpent){
        $this->hoursSpent = $hoursSpent;
    }

    public function getHoursSpent(){
        return $this->hoursSpent;
    }

    public function hasChildren() {
        return $this->ChildTasks->length() > 0;
    }

    public function getChild($i) {
        return $this->ChildTasks->getItem($i);
    }

    public function __toString() {
      $result = parent::getDescription(); 

      $result .= "<li>Horas estimadas: " . $this->getEstimatedHours() . "</li>";
      $result .= "<li>Horas totales: " . $this->getHoursSpent() . "</li>";

      if ($this->hasChildren()) {
          $result .= "<ul>";
          for ($i = 0; $i < $this->ChildTasks->length(); $i++) {
              $result .= $this->ChildTasks->getItem($i)->__toString();
          }
          $result .= "</ul>";
      } else {
          $result .= "</ul>";
      }

      return $result;
  }
}

class FixedBudgetTask extends AbstractTask {
    private $budget;
    private $ChildTasks;

    public function __construct($title, $date, $dueDate, $assignedTo, $description, $budget){
        parent::setTitle($title);
        parent::setDate($date);
        parent::setDueDate($dueDate);
        parent::setAssignedTo($assignedTo);
        parent::setDescription($description);
        $this->setBudget($budget);
        $this->ChildTasks = new TaskCollection();
    }

    public function add(AbstractTask $childTask){
        $this->ChildTasks->addTask($childTask);
    }

    public function remove(AbstractTask $childTask){
        $this->ChildTasks->removeItem($childTask);
    }

    public function setBudget($budget){
        $this->budget = $budget;
    }

    public function getBudget(){
        return $this->budget;
    }

    public function hasChildren() {
        return $this->ChildTasks->length() > 0;
    }

    public function getChild($i) {
        return $this->ChildTasks->getItem($i);
    }

    public function getDescription(){
        parent::getDescription();
        echo "<li>Presupuesto: " . $this->getBudget() . "</li>";
        if ($this->hasChildren()){
            echo "<ul>";
            for ($i = 0; $i < $this->ChildTasks->length(); $i++) {
                $this->ChildTasks->getItem($i)->getDescription();
            }
            echo "</ul>";
        } else {
            echo "</ul>";
        }
    }
}

$Workitems = new TaskCollection();
$Workitems->addTask(new TimeBasedTask('Tarea con Tiempo', "2024-01-11", "2024-01-18", "Usuario", "Descripción", 10, 5), "1");
$Workitems->addTask(new TimeBasedTask("Tarea con Tiempo2", "2024-01-11", "2024-01-18", "Usuario", "Descripción", 11, 6), "2");


$tarea1 = $Workitems->getItem("1");
print $tarea1;
print "<br>";

$Workitems->removeItem("1"); 
print "Removed: Tarea 1<br>";

try {
    $tarea1 = $Workitems->getItem("1"); 
} catch (KeyInvalidException $kie) {
    print "The collection doesn't contain anything called '1'";
}




$timeBasedTask = new TimeBasedTask('Tarea con Tiempo', "2024-01-11", "2024-01-18", "Usuario", "Descripción", 10, 5);
$timeBasedTask2 = new TimeBasedTask("Tarea con Tiempo2", "2024-01-11", "2024-01-18", "Usuario", "Descripción", 11, 6);
$timeBasedTask->add($timeBasedTask2);

$project = new Project("Proyecto Principal", "2024-01-31", "2024-02-01", "Asistente" , "Proyecto 1 desc" , 20);

$fixedBudgetTask = new FixedBudgetTask("Tarea con Presupuesto", "2024-02-02", "2024-02-03", "Munuera", "Maletín forrado al comité", 200000);
$subTask = new TimeBasedTask("Tarea con tiempo 3", "2024-01-12", "2024-01-15", "Usuario", "Descripción Subtarea", 8, 3);
$fixedBudgetTask->add($subTask);
$project->add($fixedBudgetTask);
$project->add($timeBasedTask);

// // Mostrar la lista de tareas
echo "<br>Lista de tareas:<br>";
$project->getDescription();