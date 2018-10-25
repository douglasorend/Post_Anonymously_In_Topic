[hr]
[center][color=red][size=16pt][b]POST ANONYMOUSLY IN TOPICS v2.8[/b][/size][/color]
[url=http://www.simplemachines.org/community/index.php?action=profile;u=253913][b]By Dougiefresh[/b][/url] -> [url=http://custom.simplemachines.org/mods/index.php?mod=4078]Link to Mod[/url]
[/center]
[hr]

[color=blue][b][size=12pt][u]Introduction[/u][/size][/b][/color]
This mod adds functionality to allow users to post anonymously within boards on the forum, and (depending on the settings) whether they can see who posted anonymously.

[color=blue][b][size=12pt][u]Admin Settings[/u][/size][/b][/color]
There are new settings under [b]Admin[/b] -> [b]Configuration[/b] -> [b]Modification Settings[/b] -> [b]PAIT[/b]:
[i]1)[/i] Which boards the users are allowed to post anonymously in.
[i]2)[/i] [b]Hide users posting anonymously[/b]
[i]3)[/i] [b]Who Can See Anonymous Poster[/b] setting controls what can be seen:
  > [b]No One.  Also disables recording Member ID[/b] - No one will ever know who made the post!  Ninja mode!
  > [b]No One[/b] - The member who posted anonymously is recorded, but no one will ever know!
  > [b]Only poster can see their anonymous post[/b] - Only the member posting anonymously can see his/her anonymous posts.
  > [b]Everyone with "See Who Posted Anonymously" permission[/b] - OP plus membergroups with [b]Who Can See Anonymous Poster[/b] permission can see anonymous posts.
[i]4)[/i] [b]See Who Posted Anonymously[/b] membergroups.
[i]5)[/i] [b]Topic Starter can see who posted anonymously?[/b] allows topic starters to see all anonymous posters in THEIR topic.
[i]6)[/i] [b]Topic Starter See Who Posted[/b] membergroups.

Admins and membergroups with the appropriate permission granted can also view who posted what anonymously, assuming that the posting setting is set to [b]"See Who Posted Anonymously" permission permitted[/b]....  Admin who don't want this feature active can disable it by going into the [b]Post Settings[/b] page and change the [b]Who Can See Anonymous Poster[/b] setting.

[color=blue][b][size=12pt][u]Related Discussion Thread[/u][/size][/b][/color]
o [url=http://www.simplemachines.org/community/index.php?topic=538075.0]Option to post Anonymously on topics[/url]
o [url=http://www.simplemachines.org/community/index.php?topic=544446.msg3897899#msg3897899]Topic Starters Can See Who Posted Anonymously[/url]

[color=blue][b][size=12pt][u]Compatibility Notes[/u][/size][/b][/color]
This mod was tested on SMF 2.0.12 and SMF 2.1 Beta 3, but should work on SMF 2.0 and up.  SMF 2.1 Beta 2 and SMF 1.x is not and will not be supported.

[color=blue][b][size=12pt][u]Changelog[/u][/size][/b][/color]
The changelog has been removed and can be seen at [url=http://www.xptsp.com/board/index.php?topic=600.msg944#msg944]XPtsp.com[/url].

[color=blue][b][size=12pt][u]Compatibility With Likes Pro mod[/u][/size][/b][/color]
If operation # 1 in the [b]Sources/Profile-View.php[/b] reads [b]Test Failed[/b] and operation # 2 is [b]Test Successful[/b], then it is safe to proceed with installation.  Operation # 2 addresses a weird change that the pro version Likes mod makes that I can't overcome any other way....

[color=blue][b][size=12pt][u]License[/u][/size][/b][/color]
[quote]Copyright (c) 2015 - 2018, Douglas Orend
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

1. Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.

2. Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
[/quote]