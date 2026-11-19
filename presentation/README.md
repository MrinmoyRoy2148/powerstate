# PowerStat BD presentation

## Deliverables

- `CSE_3210_System_Design_Lab_Final_Presentation_Academic_Completeness_Updated.pptx` — editable 16:9 PowerPoint with 10 numbered main slides followed by four appendix slides, including revised completed-progress and remaining-requirements tables.
- `generate_presentation.py` — source used to build the slide deck; most content and diagrams are editable PowerPoint shapes and text.
- `assets/powerstat-home-preview.png` and `assets/powerstat-admin-login-preview.png` — actual screenshots of the implemented public homepage and administrator sign-in.

## Regenerate

From the repository root, install `python-pptx` in your Python environment if needed, then run:

```powershell
python -m pip install python-pptx
python .\presentation\generate_presentation.py
```

The generator expects both screenshots at the paths above.

## Evidence notes

- Both screenshots were captured locally at 1600 x 900 using an isolated SQLite database. The homepage's zero counts and empty states are not production data.
- The main diagrams are a UML use case diagram, Level 1 DFD, outage-report sequence diagram, and admin-outage activity diagram. Supporting slides include an architecture flow and relational data model slices.
- Page 1 uses editable placeholders for student name, student ID, department, university, teacher name, and submission date because those details were not found in the repository.
- `PowerStat_BD_Requirements.docx` is used as a proposed, desk-based requirements baseline. It explicitly states that no interviews, questionnaire results, site observations, user validation, or utility approval are documented.
- Requirements are marked implemented, partial, proposed, or out of scope based on the baseline and the reviewed application code.
- The test summary records the verified local run: 34 tests passed and 150 assertions with PHPUnit 12.5.39 and PHP 8.5.9 using PDO SQLite.
