---
title: Preview Features in Let's Connect! 3.x
published: 2022-08-25
---

Let's Connect! has kept evolving since the release of 3.0 on 25 May 2022. And 
you can check the 
[roadmap](https://github.com/eduvpn/documentation/blob/v3/ROADMAP.md) for 
what's coming.

We've introduced the concept of "Preview Features" that allow to experiment 
with specific new features as requested by organizations. We can now release 
some features, check that they are really useful, collect feedback without 
fully committing to supporting them for the lifetime of the software just yet.

Some features that fall in this category are:

* Support for [running a script/command](https://github.com/eduvpn/documentation/blob/v3/SCRIPT_CONNECTION_HOOK.md) on client _connect_ and _disconnect_
* Support for [deleting OAuth authorization](https://github.com/eduvpn/documentation/blob/v3/PORTAL_CONFIG.md#remove-authorization-on-disconnect) on client _disconnect_

Other items we are working on that may become a "Preview Feature" first are:

* Implementation of an [Admin API](https://github.com/eduvpn/documentation/blob/v3/ADMIN_API.md) for scripting against the VPN Portal
* [Store secrets in the Database](https://todo.sr.ht/~eduvpn/server/86) to make [HA](https://github.com/eduvpn/documentation/blob/v3/HA.md) setups easier to manage
* Make VPN Session Expiry [depend on User](https://todo.sr.ht/~eduvpn/server/88) instead of global setting

We are very much looking forward to hearing your feedback on any of these
features and hope to work with you on improving them if you intend to use them.

If you have suggestions for other features that we did not yet mention here,
nor listed in our [issue tracker](https://todo.sr.ht/~eduvpn/server), please
let us know!