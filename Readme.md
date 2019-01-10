Hevelop Page Cache Invalidate 2.x
=====================

This module add some features to magento 2 varnish cache invalidation.

**ATTENTION:**

magento 2 default varnish vcl configuration does not allow to purge varnish without sending header parameter "X-Magento-Tags-Pattern" or "X-Pool".
So make sure to check varnish vcl configuration first.

Developer
---------
Enrico Tessari @ [https://hevelop.com](https://hevelop.com)

Licence
-------
[GNU AFFERO GENERAL PUBLIC LICENSE 3.0](https://www.gnu.org/licenses/agpl-3.0.en.html)

Copyright
---------
(c) 2019 Hevelop