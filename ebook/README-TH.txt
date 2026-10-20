วิธีใช้งาน
1) เปิด index.html ที่โฟลเดอร์หลักเพื่อดูหน้ารวม E-Book
2) แต่ละ E-Book อยู่ในโฟลเดอร์ composite, liger-profile, mactac และ tigon
3) ใส่รูปภาพแต่ละหน้าไว้ในโฟลเดอร์ img ของแต่ละ E-Book
4) รูปหน้า E-Book ใช้ขนาด A4 แนวตั้ง อัตราส่วน 794 x 1123 px หรือ 1240 x 1754 px
5) ชื่อไฟล์ตัวอย่าง:
   composite/img/color-chart-composite-skyhigh (1).webp
   mactac/img/mactac (1).webp
   tigon/img/tigon (1).webp
   liger-profile/img/liger-profile (1).webp
6) หากจำนวนหน้าไม่ใช่ 8 หน้า ให้เพิ่ม/ลบ <div class="page"> ใน index.html ของ E-Book เล่มนั้น
7) อัปโหลดทั้งโฟลเดอร์ขึ้น GitHub แล้วเปิด GitHub Pages ที่ branch main / root


============================================================
การกำหนดขนาด E-Book แยกแต่ละเล่ม
============================================================
ระบบรองรับทั้ง 2 รูปแบบในเว็บไซต์เดียวกัน:

1) A4 แนวตั้ง
   BOOK_FORMAT = 'a4-portrait'
   ขนาดอ้างอิง 794 x 1123 px

2) ครึ่ง A4 แนวนอน (A5 แนวนอน)
   BOOK_FORMAT = 'half-a4-landscape'
   ขนาดอ้างอิง 794 x 561 px

เปิดไฟล์ index.html ภายในโฟลเดอร์ของ E-Book ที่ต้องการ เช่น:
- composite/index.html
- liger-profile/index.html
- mactac/index.html
- tigon/index.html

ค้นหาบรรทัด:
const BOOK_FORMAT = 'a4-portrait';

แล้วเปลี่ยนเป็นรูปแบบที่ต้องการ โดยแต่ละเล่มตั้งค่าไม่เหมือนกันได้

ชุดตัวอย่างนี้ตั้งค่าเริ่มต้นดังนี้:
- Composite: A4 แนวตั้ง
- Liger Profile: A4 แนวตั้ง
- Mactac: A4 แนวตั้ง
- TIGON: ครึ่ง A4 แนวนอน
