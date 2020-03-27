---
title: Let's Connect! 2.0
published: 2019-04-17
---

Last week Let’s Connect! VPN server 2.0 has been released. This new version has been engineered to be more robust. Based on experiences in the field, like how we managed group-membership, we have decided to change a few components. The current Let’s Connect! VPN client apps (Windows, MacOS, Android, iOS) are fully compatible with the new server.

Two factor (2FA) authentication has been moved from the VPN layer to the authentication layer (SAML or LDAP). 2FA will be part of the authentication flow, so whenever a new device wants to get access to the VPN, the second factor must be provided.

Furthermore it is possible to configure an “expiry”, which means the user will need to authenticate and provide their 2FA periodically. The main reason to do this is to no longer depend on the VPN technology itself to provide support for 2FA and to have the ability to use other 2FA mechanisms like third-party 2FA
services and FIDO2 (WebAuthn) in the future.

Let’s Connect! VPN 2.0 supports user-groups. Basically you can configure a VLAN/IP range and a specific VPN session time per user group. For example, a group for sys admins with network access to consoles of servers and a normal user group with limited access. Users can join multiple groups and a profile will be shown per group in the Let’s Connect! VPN client.

For Let’s Connect! VPN 1.x server we were using VOOT and LDAP for group membership. The VOOT protocol allowed us to dynamically query which groups a user belongs to. So the Let’s Connect! VPN server had direct awareness if a user was added or removed to a certain group. Unfortunately we found out VOOT didn’t gain enough traction in the international NREN community. To convey authorization information for access to VPN profiles, we decided to use LDAP / SAML attributes. For example the “memberOf”, “eduPersonEntitlement” or “eduPersonAffiliation” attributes. This means Let’s Connect! VPN server only retrieves group membership information after IdP login. Since we moved 2FA to the browser, the 2FA trigger is a re-login too and will refresh group membership information.

For more details about the 2.0 changes please see this email.
