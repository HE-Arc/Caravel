New-Item -Force -ItemType directory -Path output | Out-Null
pandoc .\resources\header.md .\userguide.md -o "output/Guide d'installation.pdf" --from markdown --template eisvogel-code --listings --citeproc --pdf-engine=xelatex
pandoc .\resources\header.md .\journal.md -o "output/Journal de travail.pdf" --from markdown --template eisvogel-code --listings --citeproc --pdf-engine=xelatex