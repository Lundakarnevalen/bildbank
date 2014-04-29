# _Bildbank_

This is a simple web frontend for displaying images stored on a server's filesystem. It is intended to simplify the access to Lundakarnevalens internal image gallery.

## Project Setup

1. Clone the repository.
2. Install Bower, see [bower.io](http://bower.io) for more info.
3. Install dependencies with Bower by running `bower install` in the root directory.
4. Make sure the sv_SE.UTF-8 locale is installed on your web server, for Ubuntu see [this answer](http://askubuntu.com/questions/76013/how-do-i-add-locale-to-ubuntu-server).

Original images must be stored in public/photo.
Thumbnails will be generated and stored in public/thumb.

All images must be stored in subdirectories under public/photo.

## Troubleshooting

* Make sure public/photo and all it's subdirectories are readable by the server.
* Make sure public/thumb and all it's subdirectories are writable by the server.
* You might want to setup a cron job for removing old thumbnails.

## License

This project is licensed under the MIT licens, see attached [license](./LICENSE).
