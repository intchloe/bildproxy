# bildproxy - Varför och Hur


För att img.bi är en jättebra tjänst som fler bör använda. Tyvärr erbjuder deras API inte att man postar data direkt till deras server då bilden behöver krypteras först. 
Detta är en lösning på problemet och som dessutom gör allting säkrare och mer integritetsvänligt.

När du postar en bild till denna server då läggs den på en tmpfs-partition. Detta betyder att den aldrig sparas på hårddisken utan den läggs i minnet. När den väl är framme så tas all EXIF-data bort och den får sedan ett nytt namn. 
Sedan krypterar imgbis's API bilden lokalt för att sedan posta bilden genom Tor till img.bi. Sedan tas bilden bort ifrån minnet genom att skriva över hela partitionen med nollor en gång.
