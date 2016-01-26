// This is pretty much the definitive guide for how to do things
// https://babeljs.io/docs/learn-es2015/
// For sublime text, search package control for "Javascript Next"
// for correct syntax highlighting.
window.vf = {};
window.vf.spartan = {}; 

// All classes should be declared in the /classes folder
// The file name should match the class name and should be appended to the 
// window.vf.yourtheme object as such: 
// 	class Tabs extends UIComponent
// 	{
// 			... code ...
// 	}
// 		
// 	window.vf.yourtheme.tabs = Tabs;
// Then access this in the other files or templates as such:
// 
// var TabController = new vf.yourtheme.tabs( ... );


// All Interfaces for classes (these should be abstract and non-concretely usable)
// should be stored in the /interfaces directory. When using jQuery for re-usable components,
// extending UIComponent ensures that we have a single version and entry point of jQuery
// being used for the Magento instance.


// All Event files should be stored in the events directory and should
// be divied up as best as possible by functionality. E.G. customizations to
// events on the checkout/cart pages should be grouped into a "cart.js" file.

// Files are loaded in the following order:
// interfaces/
// classes/
// events/
// 
// Meaning that anything within those directories should not have 
// an immediate dependency upon one another.