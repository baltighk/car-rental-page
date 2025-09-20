<Baltigh Kristóf>
<JRJ0QW>
Webprogramozás – PHP beadandó
Kijelentem, hogy ez a megoldás a saját munkám. Nem másoltam vagy használtam harmadik féltől származó megoldásokat. Nem továbbítottam megoldást hallgatótársaimnak, és nem is tettem közzé. Nem használtam mesterséges intelligencia által generált kódot, kódrészletet. Az ELTE HKR 377/A. § értelmében, ha nem megengedett segédeszközt veszek igénybe, vagy más hallgatónak nem megengedett segítséget nyújtok, a tantárgyat nem teljesíthetem.

ELTE Hallgatói Követelményrendszer, IK kari különös rész, 377/A. §: "Az a hallgató, aki olyan tanulmányi teljesítménymérés (vizsga, zárthelyi, beadandó feladat) során, amelynek keretében számítógépes program vagy programmodul elkészítése a feladat, az oktató által meghatározottakon kívül más segédeszközt vesz igénybe, illetve más hallgatónak meg nem engedett segítséget nyújt, tanulmányi szabálytalanságot követ el, ezért az adott félévben a tantárgyat nem teljesítheti és a tantárgy kreditjét nem szerezheti meg."

### Minimálisan teljesítendő (enélkül nem fogadjuk el, 6 pont) 
- [X] 0.0 pont Readme.md fájl: kitöltve, feltöltve 
- [X] 1.0 pont Főoldal: az összes autó és a hozzájuk tartozó alapadatok kilistázódnak 
- [X] 1.0 pont Főoldal: az autó kártyájára/nevére kattintva a megfelelő autó aloldalára jutunk 
- [X] 1.0 pont Autóoldal: Megjelennek az autó adatai és képe 
- [X] 1.0 pont Főoldal: A főoldalon a feladatban meghatározott elemekre - kivéve a szabad időpontokra - sikeresen tudunk szűrni 
- [X] 2.0 pont Admin: Új autót tudunk létrehozni hibakezeléssel, és sikeresen menti megfelelő adatok esetén. (Ehhez bejelentkezni nem szükséges) (BEJELENTKEZÉSSEL ELKÉSZÜLT)

### Az alap feladatok (14 pont) 
- [X] 1.0 pont Hitelesítés: A regisztráció hibakezeléssel működik 
- [X] 1.0 pont Hitelesítés: A bejelentkezés hibakezeléssel működik 
- [X] 1.0 pont Hitelesítés: Sikeres bejelentkezés esetén az oldalakon látszódik, hogy be vagyunk jelentkezve 
- [X] 1.0 pont Kijelentkezés: Profiloldalon és minden oldalon elérhető 
- [ ] 2.0 pont Autóoldal: A kiválasztott autót le tudom foglalni két időpont között, sikeres foglalás esetén a foglalás elmentődik 
- [ ] 1.0 pont Autóoldal: Sikeres és sikertelen foglalás esetén a felhasználó értesítve van, sikeres esetén megjelennek a foglalás és az autó adatai 
- [X] 1.0 pont Főoldal: A főoldalon tudunk szűrni a szabad időpontokra is 
- [ ] 1.0 pont Profiloldal: Megjelennek a felhasználó korábbi foglalásai 
- [ ] 1.0 pont Admin: Az admin bejelentkezése esetén a profil oldalán megjelenik az összes foglalás, ezek a foglalások törölhetőek 
- [X] 1.0 pont Admin: Autók adatainak módosítása (hibakezeléssel) 
- [X] 1.0 pont Admin: Autók törlése 
- [X] 2.0 pont Megjelenés: Igényes, mobilbarát megjelenés 

### Plusz feladatok (max plusz 5 pont) 
- [ ] 3.0 pont Autó foglalása: Egy autó esetén eleve csak a szabad időpontokat tudjuk kijelölni foglalás esetén, például egy naptár nézetben vizualizálva vannak a szabad időpontok 
- [ ] 2.0 pont AJAX használata: A foglalás után a mentés és visszajelzés AJAX segítségével történik, nem új oldalra irányít minket, hanem például egy általad készített felugró ablakban (nem alertben!) jelez vissza az oldal frissítése nélkül.