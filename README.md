# FlexPress acf component

## Install with Pimple
The acf component uses three classes
- AbstractFieldGroup - You extend this to add acf field groups
- AbstractFieldProxy - You extend this to add acf fields, it works as a proxy as the way acf fields work makes them create the hooks too early, so by using a proxy we can make it add hooks to register them at the correct time.
- Helper - Helper to add the field groups and fields, fields register themselves and field groups use register_field_group to register.

Lets create a pimple config for both of all of these:

```
$pimple["documentDetailsFieldGroup"] = function () {
  return new DocumentDetails();
};

$pimple["flexibleLayoutProxy"] = function ($c) {
  return new FlexibleLayoutProxy($c);
};

 $pimple['ACFHelper'] = function ($c) {
    return new ACFHelper($c['objectStorage'], $c['objectStorage'], 
    array(
      $c['documentDetailsFieldGroup']
    ),
    array(
      $c['flexibleLayoutProxy']
    ));
};
```
- Note the dependency $c['objectStorage']  is a SPLObjectStorage

## Creating a concreate AbstractFieldGroup class
Create a concreate class that implements the AbstractFieldGroup class and implements the getconfig() method.

```
class DocumentDetails extends AbstractFieldGroup {

    public function getConfig()
    {
        return array(
          // config would be set in here
        )
    }

}
```

What we have in the example above a class that has the method getConfig(), this returns the config array that you get when exporting a acf field group.

And that's it, next lets make a AbstractFieldProxy

## Creating a concreate AbstractFieldProxy class

For a AbstractFieldProxy class we just need to extends the AbstractFieldProxy class and implement the getDICName method, lets do that now:

```
class DocumentDetails extends AbstractFieldProxy {

    public function getDICName()
    {
        return 'flexibleLayoutField';
    }

}
```

What this does by extends the AbstractFieldProxy it adds a hook for acf/register_fields and uses the DIC you pass as a dependency to it along with the name you specify in the getDICName method to create an instance of the field in the acf/register_fields action.

## Usage

As previously stated the AbstractFieldProxy automatically hooks itself up but for the field groups you need to call registerFieldGroups on the helper like this:

```
$helper = $pimple['ACFHelper'];
$helper->registerFieldGroups();
```

## Public methods - Helper
- registerFieldGroups() - Registers all the field groups attached to the helper

## Public methods - AbstractFieldProxy
- registerField() - Registers the field by using the DIC and the dic config name specified in getDICName().
- getDICName() - Used to specify the config name in the DIC.

## Public methods - AbstractFieldGroup
- getConfig() - Used to return the acf field group config array.
