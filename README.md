# bildproxy - Varför och Hur

För att img.bi är en jättebra tjänst som fler bör använda. Tyvärr erbjuder deras API inte att man postar data direkt till deras server då bilden behöver krypteras först. 
Detta är en lösning på problemet och som dessutom gör allting säkrare och mer integritetsvänligt.

När du postar en bild till denna server då läggs den på en tmpfs-partition. Detta betyder att den aldrig sparas på hårddisken utan den läggs i minnet. När den väl är framme så tas all EXIF-data bort och den får sedan ett nytt namn. 
Sedan krypterar imgbis's API bilden lokalt för att sedan posta bilden genom Tor till img.bi. Sedan tas bilden bort ifrån minnet genom att skriva över hela partitionen med nollor en gång. 

För att skapa en tmpfs-partition så använder du följande:
`mount -t tmpfs -o size=10m tmpfs /var/www/uploads`
Du bör dessutom lägga till detta i din fstab(/etc/fstab):

`tmpfs       /var/www/uploads tmpfs   nodev,nosuid,noexec,nodiratime,size=10M   0 0`

Det du behöver är:
`sudo apt-get install libimage-exiftool-perl tor proxychains secure-delete`

Du behöver node för npm och imgbi-client. Följ denna guide för att fixa node: http://howtonode.org/how-to-install-nodejs - sedan installerar du imgbi-client med `npm install imgbi -g`
