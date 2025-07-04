import os
import csv
from PIL import Image
import pytesseract

pytesseract.pytesseract.tesseract_cmd = r"C:\Program Files\Tesseract-OCR\tesseract.exe"

# === CONFIG ===
CARTELLA_INPUT = "RokScreenInput"         # Cartella contenente gli screenshot
FILE_OUTPUT = "output/RokScreen - 21.06.2025.csv"         # File CSV di output
ESTENSIONI_AMMESSE = ('.png', '.jpg', '.jpeg')

# === COORDINATE FISSE per ciascun campo (x1, y1, x2,x y2) ===
CAMPI = {
    "name": (709, 164, 892, 204),
    "power": (1211, 160, 1342, 204),
    "helps": (1718, 899, 1811, 1000),
}

def pulisci_numero(testo):
    return testo.replace(",", "").replace(".", "").replace(" ", "").strip()

def estrai_campi_da_immagine(percorso_img):
    img = Image.open(percorso_img)
    dati = {}
    for campo, box in CAMPI.items():
        area = img.crop(box)
        testo = pytesseract.image_to_string(area, config="--psm 6").strip()
        if campo in ["power", "helps"]:
            testo = pulisci_numero(testo)
        dati[campo] = testo
    return dati

def processa_cartella(cartella):
    risultati = []
    for idx, nome_file in enumerate(os.listdir(cartella), start=1):
        if nome_file.lower().endswith(ESTENSIONI_AMMESSE):
            path_img = os.path.join(cartella, nome_file)
            print(f"Elaboro: {nome_file}")
            dati = estrai_campi_da_immagine(path_img)
            dati["file"] = nome_file
            dati["id"] = idx
            risultati.append(dati)
    return risultati

def salva_csv(dati, path_output):
    if not dati:
        print("Nessun dato estratto.")
        return
    os.makedirs(os.path.dirname(path_output), exist_ok=True)
    intestazioni = ["id", "file"] + list(CAMPI.keys())
    with open(path_output, mode="w", newline="", encoding="utf-8") as f:
        writer = csv.DictWriter(f, fieldnames=intestazioni)
        writer.writeheader()
        writer.writerows(dati)
    print(f"Dati salvati in: {path_output}")

if __name__ == "__main__":
    risultati = processa_cartella(CARTELLA_INPUT)
    salva_csv(risultati, FILE_OUTPUT)