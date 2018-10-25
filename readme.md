------

## POST ANONYMOUSLY IN TOPICS v2.10

[**By Dougiefresh**](http://www.simplemachines.org/community/index.php?action=profile;u=253913) -> [Link to Mod](http://custom.simplemachines.org/mods/index.php?mod=4078)

------

## Introduction
This mod adds functionality to allow users to post anonymously within boards on the forum, and (depending on the settings) whether they can see who posted anonymously.

## Admin Settings
There are new settings under **Admin** -> **Configuration** -> **Modification Settings** -> **PAIT**:
(1) Which boards the users are allowed to post anonymously in.
(2) **Hide users posting anonymously**
(3) **Who Can See Anonymous Poster** setting controls what can be seen:
  > **No One.  Also disables recording Member ID** - No one will ever know who made the post!  Ninja mode!
  > **No One** - The member who posted anonymously is recorded, but no one will ever know!
  > **Only poster can see their anonymous post** - Only the member posting anonymously can see his/her anonymous posts.
  > **Everyone with "See Who Posted Anonymously" permission** - OP plus membergroups with **Who Can See Anonymous Poster** permission can see anonymous posts.
(4) **See Who Posted Anonymously** membergroups.
(5) **Topic Starter can see who posted anonymously?** allows topic starters to see all anonymous posters in THEIR topic.
(6) **Topic Starter See Who Posted** membergroups.

Admins and membergroups with the appropriate permission granted can also view who posted what anonymously, assuming that the posting setting is set to **"See Who Posted Anonymously" permission permitted**....  Admin who don't want this feature active can disable it by going into the **Post Settings** page and change the **Who Can See Anonymous Poster** setting.

## Related Discussion Thread

- [Option to post Anonymously on topics](http://www.simplemachines.org/community/index.php?topic=538075.0)
- [Topic Starters Can See Who Posted Anonymously](http://www.simplemachines.org/community/index.php?topic=544446.msg3897899#msg3897899)

## Compatibility Notes
This mod was tested on SMF 2.0.12 and SMF 2.1 Beta 3, but should work on SMF 2.0 and up.  SMF 2.1 Beta 2 and SMF 1.x is not and will not be supported.

[Board color and icons](https://custom.simplemachines.org/mods/index.php?mod=3023) mod should be installed **AFTER** this mod, otherwise you will have a minor (but easily manually fixable) conflict.

## Translators

- Spanish Latin: [Rock Lee](https://www.simplemachines.org/community/index.php?action=profile;u=322597)

## Changelog
The changelog can be viewed at [XPtsp.com](http://www.xptsp.com/board/free-modifications/post-anonymously-in-topic/?tab=1).

## Compatibility With Likes Pro mod
If operation # 1 in the **Sources/Profile-View.php** reads **Test Failed** and operation # 2 is **Test Successful**, then it is safe to proceed with installation.  Operation # 2 addresses a weird change that the pro version Likes mod makes that I can't overcome any other way....

## License
Copyright (c) 2015 - 2018, Douglas Orend

All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

1. Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.

2. Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
