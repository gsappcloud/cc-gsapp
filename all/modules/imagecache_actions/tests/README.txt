This module provides an overview of all the imagecache presets in use on this 
site, as well as a number of samples used to test if everything is working as 
expected.

Sample images are provided to illustrate a number of the sample presets.
Each image shown should match the one next to it. 
Where they don't match illustrates a current weakness in the system or the code 
coverage.

== Samples ==
Samples are provided in a format that is compatible with imagecache 'features' 
exports, as arrays of imagecache presets.
However, unlike the features export process that saves them in one large file, 
Each sample provided here is in its own .inc file (for modular management).
Our implementation of HOOK_imagecache_default_presets in 
imagecache_testsuite.features.inc scans our directory for such files and then 
compiles that list for us.

== To add a new sample ==

* Either create it by hand, based on examples, or make your imagecache preset
and export it as a 'feature'. 

* Copy that configuration array into a file named 
{presetname}.imagecache_preset.inc.
That file can be placed either here in the imagecache_testsuite folder,
or in a subdirectory called 'tests' local to the action module being tested.

* Add the named preset to the list found in imagecache_testsuite.info. 

* Provide a sample image illustrating what the preset is SUPPOSED to look like.
  Name it as {presetname}.jpg. Either .jpg or .png files are allowed, the 
  system will look for both when generating the side-by-side preview.
  Place it next to the test {presetname}.imagecache_preset.inc file.
  
== Limitations ==
We cannot require dependencies - additional modules - using this method.
Image generated without certain imagecache_actions being enabled will be 
highlighted as incomplete or unavailable.

