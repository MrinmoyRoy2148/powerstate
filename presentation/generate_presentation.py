from math import atan2, cos, degrees, sin
from pathlib import Path

from pptx import Presentation
from pptx.dml.color import RGBColor
from pptx.enum.dml import MSO_LINE_DASH_STYLE
from pptx.enum.shapes import MSO_CONNECTOR, MSO_SHAPE
from pptx.enum.text import MSO_ANCHOR, PP_ALIGN
from pptx.oxml.ns import qn
from pptx.util import Inches, Pt


ROOT = Path(__file__).resolve().parent
OUTPUT = ROOT / "CSE_3210_System_Design_Lab_Final_Presentation_Academic_Completeness_Updated.pptx"
HOME_SCREENSHOT = ROOT / "assets" / "powerstat-home-preview.png"
ADMIN_SCREENSHOT = ROOT / "assets" / "powerstat-admin-login-preview.png"

WIDTH = 13.333
HEIGHT = 7.5
NAVY = "17324D"
BLUE = "527A9A"
CHARCOAL = "333333"
MID = "5D6872"
GRAY = "E8EAED"
PALE = "F5F6F7"
WHITE = "FFFFFF"
GREEN = "2E6B45"
AMBER = "95620C"
RED = "A3423F"
FONT = "Aptos"


def color(value: str) -> RGBColor:
    return RGBColor.from_string(value)


def no_theme_effect(shape):
    style = shape._element.find(qn("p:style"))
    if style is not None:
        effect = style.find(qn("a:effectRef"))
        if effect is not None:
            effect.set("idx", "0")
    return shape


def text(
    slide,
    value,
    x,
    y,
    w,
    h,
    size=14,
    fill=CHARCOAL,
    bold=False,
    align=PP_ALIGN.LEFT,
    valign=MSO_ANCHOR.TOP,
    margin=0.03,
):
    shape = slide.shapes.add_textbox(Inches(x), Inches(y), Inches(w), Inches(h))
    no_theme_effect(shape)
    frame = shape.text_frame
    frame.clear()
    frame.word_wrap = True
    frame.margin_left = Inches(margin)
    frame.margin_right = Inches(margin)
    frame.margin_top = Inches(margin)
    frame.margin_bottom = Inches(margin)
    frame.vertical_anchor = valign
    paragraph = frame.paragraphs[0]
    paragraph.alignment = align
    paragraph.space_before = Pt(0)
    paragraph.space_after = Pt(0)
    paragraph.font.name = FONT
    paragraph.font.size = Pt(size)
    paragraph.font.bold = bold
    paragraph.font.color.rgb = color(fill)
    paragraph.text = value
    return shape


def box(slide, x, y, w, h, fill=WHITE, stroke=NAVY, line_width=1):
    shape = slide.shapes.add_shape(
        MSO_SHAPE.RECTANGLE,
        Inches(x),
        Inches(y),
        Inches(w),
        Inches(h),
    )
    no_theme_effect(shape)
    shape.fill.solid()
    shape.fill.fore_color.rgb = color(fill)
    shape.line.color.rgb = color(stroke)
    shape.line.width = Pt(line_width)
    return shape


def ellipse(slide, x, y, w, h, fill=WHITE, stroke=NAVY, line_width=1):
    shape = slide.shapes.add_shape(
        MSO_SHAPE.OVAL,
        Inches(x),
        Inches(y),
        Inches(w),
        Inches(h),
    )
    no_theme_effect(shape)
    shape.fill.solid()
    shape.fill.fore_color.rgb = color(fill)
    shape.line.color.rgb = color(stroke)
    shape.line.width = Pt(line_width)
    return shape


def line(slide, x1, y1, x2, y2, stroke=CHARCOAL, width=1, dashed=False, arrow=False):
    shape = slide.shapes.add_connector(
        MSO_CONNECTOR.STRAIGHT,
        Inches(x1),
        Inches(y1),
        Inches(x2),
        Inches(y2),
    )
    no_theme_effect(shape)
    shape.line.color.rgb = color(stroke)
    shape.line.width = Pt(width)
    if dashed:
        shape.line.dash_style = MSO_LINE_DASH_STYLE.DASH
    if arrow:
        angle = atan2(y2 - y1, x2 - x1)
        cx = x2 - cos(angle) * 0.06
        cy = y2 - sin(angle) * 0.06
        head = slide.shapes.add_shape(
            MSO_SHAPE.ISOSCELES_TRIANGLE,
            Inches(cx - 0.06),
            Inches(cy - 0.06),
            Inches(0.12),
            Inches(0.12),
        )
        no_theme_effect(head)
        head.rotation = degrees(angle) + 90
        head.fill.solid()
        head.fill.fore_color.rgb = color(stroke)
        head.line.color.rgb = color(stroke)
        head.line.width = Pt(0.25)
    return shape


def white_background(slide):
    slide.background.fill.solid()
    slide.background.fill.fore_color.rgb = color(WHITE)


def footer(slide, page):
    line(slide, 0.7, 7.08, 12.63, 7.08, GRAY, 0.7)
    text(slide, "PowerStat BD  |  CSE 3210 — System Design Lab", 0.72, 7.15, 5.2, 0.18, 8, MID)
    text(slide, page, 11.9, 7.14, 0.68, 0.19, 8, MID, align=PP_ALIGN.RIGHT)


def header(slide, page, title, section, subtitle=None):
    white_background(slide)
    text(slide, section, 0.72, 0.3, 5.7, 0.23, 9.5, BLUE, bold=True)
    text(slide, page, 11.9, 0.3, 0.68, 0.23, 9, MID, align=PP_ALIGN.RIGHT)
    text(slide, title, 0.72, 0.72, 11.9, 0.53, 29, NAVY, bold=True)
    if subtitle:
        text(slide, subtitle, 0.74, 1.34, 11.8, 0.33, 12.5, MID)
    footer(slide, page)


def actor(slide, cx, cy, label, name_width=1.5):
    ellipse(slide, cx - 0.11, cy - 0.42, 0.22, 0.22, WHITE, CHARCOAL, 1)
    line(slide, cx, cy - 0.19, cx, cy + 0.25, CHARCOAL, 1)
    line(slide, cx - 0.23, cy - 0.02, cx + 0.23, cy - 0.02, CHARCOAL, 1)
    line(slide, cx, cy + 0.25, cx - 0.18, cy + 0.5, CHARCOAL, 1)
    line(slide, cx, cy + 0.25, cx + 0.18, cy + 0.5, CHARCOAL, 1)
    text(slide, label, cx - name_width / 2, cy + 0.54, name_width, 0.3, 10.5, CHARCOAL, align=PP_ALIGN.CENTER)


def table(
    slide,
    x,
    y,
    widths,
    headers,
    rows,
    row_height,
    font_size=11,
    header_height=0.42,
    status_column=None,
):
    total_width = sum(widths)
    box(slide, x, y, total_width, header_height, NAVY, NAVY, 0.5)
    cursor_x = x
    for label, width in zip(headers, widths):
        text(slide, label, cursor_x + 0.1, y + 0.1, width - 0.18, header_height - 0.17, 9.5, WHITE, True, valign=MSO_ANCHOR.MIDDLE)
        cursor_x += width

    for row_index, row in enumerate(rows):
        row_y = y + header_height + row_index * row_height
        fill = WHITE if row_index % 2 == 0 else PALE
        box(slide, x, row_y, total_width, row_height, fill, GRAY, 0.45)
        cursor_x = x
        for column, (value, width) in enumerate(zip(row, widths)):
            text_fill = CHARCOAL
            bold = column == 0
            if status_column == column:
                status = value.lower()
                if "implemented" in status or "passed" in status:
                    text_fill = GREEN
                elif "partial" in status:
                    text_fill = AMBER
                elif "out of scope" in status or "not implemented" in status:
                    text_fill = RED
                else:
                    text_fill = BLUE
                bold = True
            text(
                slide,
                value,
                cursor_x + 0.1,
                row_y + 0.07,
                width - 0.18,
                row_height - 0.12,
                font_size,
                text_fill,
                bold,
                valign=MSO_ANCHOR.MIDDLE,
            )
            cursor_x += width


def slide_1(prs):
    slide = prs.slides.add_slide(prs.slide_layouts[6])
    white_background(slide)
    text(slide, "CSE 3210  |  SYSTEM DESIGN LAB  |  FINAL PROJECT", 0.75, 0.55, 7.5, 0.25, 10, BLUE, True)
    text(slide, "PowerStat BD", 0.75, 1.05, 6.7, 0.58, 34, NAVY, True)
    text(
        slide,
        "A Bangladesh-focused portal for area-based outage, schedule and service-notice information.",
        0.77,
        1.75,
        6.6,
        0.78,
        18,
        CHARCOAL,
    )
    text(slide, "PROJECT INTERFACE", 0.77, 2.85, 2.0, 0.22, 9, BLUE, True)
    slide.shapes.add_picture(str(HOME_SCREENSHOT), Inches(0.77), Inches(3.2), width=Inches(6.6), height=Inches(3.71))
    text(slide, "Actual public homepage; empty local database shown.", 0.8, 6.94, 6.5, 0.18, 8.5, MID)

    line(slide, 7.8, 0.78, 7.8, 6.85, GRAY, 1)
    text(slide, "PROJECT", 8.2, 1.06, 1.7, 0.2, 9, BLUE, True)
    text(slide, "PowerStat BD", 8.2, 1.35, 4.1, 0.32, 16, NAVY, True)
    text(
        slide,
        "Public information portal; administrators maintain records and visitors browse published updates.",
        8.2,
        1.82,
        4.25,
        0.72,
        13,
        CHARCOAL,
    )
    line(slide, 8.2, 2.72, 12.45, 2.72, GRAY, 0.8)
    details = [
        ("Student", "[Student name]"),
        ("Student ID", "[Student ID]"),
        ("Department / university", "[Department] / [University]"),
        ("Teacher", "[Teacher name]"),
        ("Submission date", "[Submission date]"),
    ]
    for index, (label, value) in enumerate(details):
        y = 2.98 + index * 0.68
        text(slide, label, 8.2, y, 1.75, 0.22, 10, MID)
        text(slide, value, 9.85, y, 2.55, 0.3, 11.5, CHARCOAL)
    text(
        slide,
        "Restoration times are estimates, not guarantees.",
        8.2,
        6.52,
        4.2,
        0.25,
        10,
        MID,
    )
    footer(slide, "01 / 10")


def slide_2(prs):
    slide = prs.slides.add_slide(prs.slide_layouts[6])
    header(
        slide,
        "02 / 10",
        "Project overview",
        "01  |  INTRODUCTION",
        "The scope and motivation below are drawn from the README, source code and proposed requirements baseline.",
    )
    text(slide, "PROBLEM CONTEXT", 0.76, 1.95, 2.0, 0.22, 9.5, BLUE, True)
    text(
        slide,
        "Residents need a clear way to find published, area-specific interruption and schedule information. The portal organizes administrator-entered updates; it is not a live grid monitor or official utility channel.",
        0.76,
        2.28,
        5.55,
        1.05,
        15,
        CHARCOAL,
    )
    text(slide, "OBJECTIVE & USERS", 6.85, 1.95, 2.7, 0.22, 9.5, BLUE, True)
    text(
        slide,
        "Help public visitors browse by service area. Give authorized administrators tools to maintain areas, outage reasons, outages, schedules and announcements.",
        6.85,
        2.28,
        5.62,
        1.05,
        15,
        CHARCOAL,
    )
    line(slide, 0.76, 3.58, 12.58, 3.58, GRAY, 0.8)
    text(slide, "SYSTEM FLOW", 0.76, 3.82, 2.0, 0.22, 9.5, BLUE, True)
    flow_y = 4.45
    steps = [
        (0.82, "Administrator", "Creates or updates\nservice records"),
        (4.72, "Laravel application", "Validates, authorizes\nand persists records"),
        (8.65, "Visitor", "Reads published\narea information"),
    ]
    for x, heading, description in steps:
        box(slide, x, flow_y, 3.08, 1.0, WHITE, NAVY, 1)
        text(slide, heading, x + 0.13, flow_y + 0.15, 2.82, 0.25, 13, NAVY, True, PP_ALIGN.CENTER)
        text(slide, description, x + 0.13, flow_y + 0.47, 2.82, 0.4, 11.5, CHARCOAL, align=PP_ALIGN.CENTER)
    line(slide, 3.97, flow_y + 0.5, 4.6, flow_y + 0.5, BLUE, 1.2, arrow=True)
    line(slide, 7.88, flow_y + 0.5, 8.52, flow_y + 0.5, BLUE, 1.2, arrow=True)
    text(
        slide,
        "Stack: PHP 8.3+ / Laravel 13 • Blade • Tailwind CSS 4 • Vite • MySQL or MariaDB (documented)",
        0.8,
        5.9,
        11.9,
        0.35,
        12,
        CHARCOAL,
    )
    text(
        slide,
        "The 3 Nov 2026 requirements document is a desk-based proposed baseline, not user-validated or authority-approved.",
        0.8,
        6.48,
        11.7,
        0.26,
        9.5,
        MID,
    )


def slide_3(prs):
    slide = prs.slides.add_slide(prs.slide_layouts[6])
    header(
        slide,
        "03 / 10",
        "Functional requirements",
        "02  |  USER REQUIREMENTS",
        "Requirement IDs and priority labels follow the proposed stakeholder baseline dated 3 November 2026.",
    )
    rows = [
        ("FR-01–02", "Must", "Public pages; area/date/type filters", "Implemented"),
        ("FR-03–04", "Must", "Outage details, history, schedules and published notices", "Implemented"),
        ("FR-05", "Must", "Bangla default; English language switch", "Implemented"),
        ("FR-06", "Must", "Unambiguous Bangladesh-local dates and times", "Partial"),
        ("FR-07–08", "Should", "Source/verification metadata; official utility contacts", "Proposed"),
        ("FR-09", "Should", "Empty states distinguish portal records from grid reality", "Partial"),
        ("FR-10", "Could", "Citizen reports, subscriber alerts and complaints", "Out of scope"),
        ("FR-11", "Must", "Protected admin sign-in; active account; login throttling", "Implemented"),
        ("FR-12–15", "Must", "Admin maintains areas, reasons, outages, schedules and notices", "Implemented"),
        ("FR-16–17", "Should", "Approval, source checks, correction and publishing governance", "Proposed"),
    ]
    table(
        slide,
        0.75,
        1.95,
        [1.32, 0.9, 7.0, 2.56],
        ["ID", "Priority", "Requirement summary", "Repository status"],
        rows,
        0.42,
        11.1,
        0.43,
        status_column=3,
    )
    text(
        slide,
        "Must / Should / Could expresses proposed priority, not an approved user-research result.",
        0.8,
        6.73,
        11.4,
        0.22,
        9.5,
        MID,
    )


def slide_4(prs):
    slide = prs.slides.add_slide(prs.slide_layouts[6])
    header(
        slide,
        "04 / 10",
        "Non-functional needs & user priorities",
        "02  |  USER REQUIREMENTS",
        "Implemented safeguards are not equivalent to verified acceptance or compliance.",
    )
    text(slide, "QUALITY REQUIREMENTS", 0.78, 1.94, 4.2, 0.23, 10, BLUE, True)
    left = [
        ("Localization", "Bangla-first and English; check all messages and dates in both locales."),
        ("Time and location", "Use Asia/Dhaka consistently; make configured service-area coverage clear."),
        ("Mobile usability", "Keep public and admin workflows usable on 360 px screens; test representative Bangla text."),
        ("Accessibility", "Keyboard, focus, semantic labels and contrast; WCAG 2.2 AA is a proposed target."),
        ("Security and privacy", "Admin authorization, validation and protected credentials; no public account is needed."),
    ]
    for i, (heading, detail) in enumerate(left):
        y = 2.32 + i * 0.72
        text(slide, heading, 0.8, y, 2.0, 0.24, 12.5, NAVY, True)
        text(slide, detail, 2.65, y, 3.7, 0.54, 11.2, CHARCOAL)
        if i < len(left) - 1:
            line(slide, 0.8, y + 0.6, 6.36, y + 0.6, GRAY, 0.55)

    line(slide, 6.67, 1.94, 6.67, 6.12, GRAY, 0.8)
    text(slide, "USER NEEDS, ASSUMPTIONS & LIMITS", 6.98, 1.94, 5.2, 0.23, 10, BLUE, True)
    right = [
        ("Visitor", "Find understandable, published updates for a service area; restoration is an estimate."),
        ("Administrator", "Maintain records through a protected console without editing application code."),
        ("Freshness", "Identify a content owner, verification source, review cadence and expiry policy before launch."),
        ("Operations", "Set deployment, backups, recovery, monitoring and performance targets before production."),
        ("Research", "Interviews and usability sessions with residents in more than one service territory remain outstanding."),
    ]
    for i, (heading, detail) in enumerate(right):
        y = 2.32 + i * 0.72
        text(slide, heading, 7.0, y, 1.65, 0.24, 12.5, NAVY, True)
        text(slide, detail, 8.58, y, 3.9, 0.54, 11.2, CHARCOAL)
        if i < len(right) - 1:
            line(slide, 7.0, y + 0.6, 12.55, y + 0.6, GRAY, 0.55)
    text(
        slide,
        "Mobile, accessibility, freshness, privacy, availability and performance needs are proposed acceptance goals; they are not reported as measured.",
        0.8,
        6.52,
        11.8,
        0.3,
        9.5,
        MID,
    )


def use_case_oval(slide, label, x, y, w=3.0, h=0.49):
    ellipse(slide, x, y, w, h, WHITE, CHARCOAL, 0.9)
    text(slide, label, x + 0.08, y + 0.12, w - 0.16, h - 0.18, 10.5, CHARCOAL, align=PP_ALIGN.CENTER, valign=MSO_ANCHOR.MIDDLE)


def slide_5(prs):
    slide = prs.slides.add_slide(prs.slide_layouts[6])
    header(
        slide,
        "05 / 10",
        "Use case diagram",
        "03  |  SYSTEM DESIGN",
        "Actors and use cases are derived from public routes and the protected admin route group.",
    )
    boundary = box(slide, 2.03, 1.95, 9.28, 4.76, WHITE, CHARCOAL, 1)
    text(slide, "PowerStat BD", 2.23, 2.04, 1.75, 0.22, 10, NAVY, True)
    actor(slide, 1.12, 4.12, "Public visitor", 1.4)
    actor(slide, 12.17, 4.12, "Administrator", 1.4)

    visitor = [
        ("Browse service areas", 2.5),
        ("View outage details and history", 3.19),
        ("Browse published schedules", 3.88),
        ("Read published announcements", 4.57),
        ("Switch Bangla / English", 5.26),
    ]
    administrator = [
        ("Sign in / sign out", 2.5),
        ("View management dashboard", 3.19),
        ("Manage areas and outage reasons", 3.88),
        ("Manage schedules and outages", 4.57),
        ("Manage announcements", 5.26),
    ]
    for label, y in visitor:
        use_case_oval(slide, label, 2.55, y, 3.35)
        line(slide, 1.43, 4.12, 2.55, y + 0.245, CHARCOAL, 0.75)
    for label, y in administrator:
        use_case_oval(slide, label, 7.15, y, 3.55)
        line(slide, 10.7, y + 0.245, 11.86, 4.12, CHARCOAL, 0.75)
    text(
        slide,
        "No include / extend relationships are required for these independently routed capabilities.",
        2.35,
        6.32,
        8.7,
        0.22,
        9.5,
        MID,
        align=PP_ALIGN.CENTER,
    )


def dfd_process(slide, label, x, y, w, h):
    ellipse(slide, x, y, w, h, WHITE, CHARCOAL, 1.2)
    text(slide, label, x + 0.12, y + 0.12, w - 0.24, h - 0.2, 10.5, CHARCOAL, True, PP_ALIGN.CENTER, MSO_ANCHOR.MIDDLE)


def data_store(slide, number, name, x, y, w):
    line(slide, x, y, x + w, y, CHARCOAL, 1)
    line(slide, x, y + 0.42, x + w, y + 0.42, CHARCOAL, 1)
    line(slide, x + 0.42, y, x + 0.42, y + 0.42, CHARCOAL, 1)
    text(slide, number, x + 0.02, y + 0.08, 0.34, 0.22, 8.8, CHARCOAL, True, align=PP_ALIGN.CENTER)
    text(slide, name, x + 0.5, y + 0.08, w - 0.58, 0.23, 9.7, CHARCOAL)


def dfd_flow(slide, x1, y1, x2, y2, label, lx, ly, lw, dashed=False):
    line(slide, x1, y1, x2, y2, CHARCOAL, 1, dashed=dashed, arrow=True)
    text(slide, label, lx, ly, lw, 0.3, 8.7, CHARCOAL, align=PP_ALIGN.CENTER)


def slide_6(prs):
    slide = prs.slides.add_slide(prs.slide_layouts[6])
    header(
        slide,
        "06 / 10",
        "Data flow diagram — Level 1",
        "03  |  SYSTEM DESIGN",
        "Processes, external entities, data stores and flows correspond to the existing Laravel application.",
    )
    text(slide, "External entities: visitor and administrator  |  D1 holds service information  |  D2 holds administrator accounts", 0.8, 1.86, 11.8, 0.25, 10.5, MID)

    # External entities
    box(slide, 0.85, 2.66, 1.35, 0.67, WHITE, CHARCOAL, 1)
    text(slide, "Visitor", 0.95, 2.88, 1.15, 0.23, 11.5, CHARCOAL, True, PP_ALIGN.CENTER)
    box(slide, 0.85, 4.76, 1.55, 0.67, WHITE, CHARCOAL, 1)
    text(slide, "Administrator", 0.93, 4.98, 1.39, 0.23, 10.2, CHARCOAL, True, PP_ALIGN.CENTER)
    box(slide, 10.98, 4.6, 1.55, 0.67, WHITE, CHARCOAL, 1)
    text(slide, "Administrator", 11.05, 4.82, 1.4, 0.23, 10.2, CHARCOAL, True, PP_ALIGN.CENTER)

    # Processes
    dfd_process(slide, "1.0\nBrowse public\ninformation", 3.05, 2.42, 2.05, 1.05)
    dfd_process(slide, "2.0\nAuthenticate\nadministrator", 3.05, 4.53, 2.05, 1.05)
    dfd_process(slide, "3.0\nMaintain service\nrecords", 7.28, 4.53, 2.05, 1.05)

    # Data stores
    data_store(slide, "D1", "Areas, outages, schedules, notices", 7.0, 2.44, 3.37)
    data_store(slide, "D2", "User accounts and admin status", 3.2, 5.97, 3.37)

    # Public browse request/results
    dfd_flow(slide, 2.2, 2.84, 3.05, 2.84, "area / date / type query", 2.17, 2.48, 1.5)
    dfd_flow(slide, 3.05, 3.18, 2.2, 3.18, "published information", 2.13, 3.24, 1.56)
    dfd_flow(slide, 5.1, 2.7, 7.0, 2.7, "read/filter records", 5.26, 2.33, 1.55)
    dfd_flow(slide, 7.0, 3.12, 5.1, 3.12, "matching records", 5.33, 3.2, 1.5)

    # Authentication and record maintenance; the administrator entity is repeated for legibility.
    dfd_flow(slide, 2.4, 4.9, 3.05, 4.9, "credentials", 2.2, 4.55, 1.1)
    dfd_flow(slide, 3.05, 5.24, 2.4, 5.24, "auth result", 2.27, 5.29, 1.1)
    line(slide, 4.0, 5.58, 4.0, 5.97, CHARCOAL, 1, arrow=True)
    line(slide, 4.35, 5.97, 4.35, 5.58, CHARCOAL, 1, arrow=True)
    text(slide, "account lookup", 5.12, 5.61, 1.25, 0.16, 7.8, CHARCOAL)
    text(slide, "account status", 5.12, 5.79, 1.25, 0.16, 7.8, CHARCOAL)
    dfd_flow(slide, 10.98, 5.03, 9.33, 5.03, "authorized record changes", 8.95, 4.65, 1.7)
    dfd_flow(slide, 9.33, 5.38, 10.98, 5.38, "validation / status", 9.47, 5.42, 1.35)
    dfd_flow(slide, 8.3, 4.53, 8.3, 2.86, "read / write published records", 8.42, 3.64, 1.75)

    text(slide, "Only administrators create or change outage records. Public routes read published information.", 0.82, 6.62, 11.8, 0.24, 9.5, MID)


def lifeline(slide, x, name, top=2.24, bottom=6.63):
    box(slide, x - 0.78, top, 1.56, 0.5, WHITE, CHARCOAL, 1)
    text(slide, name, x - 0.69, top + 0.1, 1.38, 0.28, 10, CHARCOAL, True, PP_ALIGN.CENTER, MSO_ANCHOR.MIDDLE)
    line(slide, x, top + 0.5, x, bottom, CHARCOAL, 0.8, dashed=True)


def sequence_message(slide, source, target, y, label, x_left, width, dashed=False):
    line(slide, source, y, target, y, CHARCOAL, 1, dashed=dashed, arrow=True)
    text(slide, label, x_left, y - 0.25, width, 0.19, 9, CHARCOAL, align=PP_ALIGN.CENTER)


def slide_7(prs):
    slide = prs.slides.add_slide(prs.slide_layouts[6])
    header(
        slide,
        "07 / 10",
        "Sequence diagram — administrator records an outage",
        "03  |  SYSTEM DESIGN",
        "Main path: an active administrator opens the form, submits valid data and receives a redirect. Invalid input returns errors and is not saved.",
    )
    xs = [1.24, 3.6, 6.08, 8.74, 11.38]
    names = ["Administrator", "Browser / Blade", "Admin routes +\nmiddleware", "Outage controller", "Database"]
    for x, name in zip(xs, names):
        lifeline(slide, x, name)
    messages = [
        (1.24, 3.6, 2.78, "GET create form", 1.3, 2.15, False),
        (3.6, 6.08, 3.1, "GET /admin/outages/create", 3.7, 2.25, False),
        (6.08, 8.74, 3.42, "authorize active admin", 6.12, 2.55, False),
        (8.74, 11.38, 3.74, "load active zones, reasons, schedules", 8.82, 2.35, False),
        (11.38, 8.74, 4.06, "form options", 9.13, 2.05, True),
        (8.74, 3.6, 4.38, "render form", 4.0, 4.35, True),
        (1.24, 3.6, 4.7, "POST outage fields", 1.38, 2.05, False),
        (3.6, 6.08, 5.02, "POST /admin/outages", 3.72, 2.2, False),
        (6.08, 8.74, 5.34, "authorize + validate request", 6.1, 2.55, False),
        (8.74, 11.38, 5.66, "check references; insert outage", 8.82, 2.35, False),
        (11.38, 8.74, 5.98, "saved record", 9.13, 2.05, True),
        (8.74, 3.6, 6.3, "redirect + success message", 4.0, 4.35, True),
    ]
    for source, target, y, label, lx, width, dashed in messages:
        sequence_message(slide, source, target, y, label, lx, width, dashed)


def activity_node(slide, label, x, y, w, h, fill=WHITE):
    shape = slide.shapes.add_shape(MSO_SHAPE.ROUNDED_RECTANGLE, Inches(x), Inches(y), Inches(w), Inches(h))
    no_theme_effect(shape)
    shape.fill.solid()
    shape.fill.fore_color.rgb = color(fill)
    shape.line.color.rgb = color(CHARCOAL)
    shape.line.width = Pt(1)
    text(slide, label, x + 0.1, y + 0.08, w - 0.2, h - 0.14, 10, CHARCOAL, align=PP_ALIGN.CENTER, valign=MSO_ANCHOR.MIDDLE)


def decision_node(slide, label, x, y, w, h):
    shape = slide.shapes.add_shape(MSO_SHAPE.DIAMOND, Inches(x), Inches(y), Inches(w), Inches(h))
    no_theme_effect(shape)
    shape.fill.solid()
    shape.fill.fore_color.rgb = color(WHITE)
    shape.line.color.rgb = color(CHARCOAL)
    shape.line.width = Pt(1)
    text(slide, label, x + 0.12, y + 0.15, w - 0.24, h - 0.3, 8.7, CHARCOAL, align=PP_ALIGN.CENTER, valign=MSO_ANCHOR.MIDDLE)


def entity(slide, name, fields, x, y, w=2.0, h=0.82):
    box(slide, x, y, w, h, WHITE, CHARCOAL, 1)
    box(slide, x, y, w, 0.26, PALE, CHARCOAL, 0.8)
    text(slide, name, x + 0.08, y + 0.045, w - 0.16, 0.18, 9.4, NAVY, True)
    text(slide, fields, x + 0.09, y + 0.33, w - 0.18, h - 0.38, 8.5, CHARCOAL)


def slide_8(prs):
    slide = prs.slides.add_slide(prs.slide_layouts[6])
    header(
        slide,
        "08 / 10",
        "Activity diagram & data model",
        "03  |  SYSTEM DESIGN",
        "The primary workflow is administrator outage entry; the adjacent ER slice reflects migration foreign keys.",
    )
    text(slide, "ACTIVITY — CREATE AN OUTAGE", 0.78, 1.93, 4.5, 0.22, 9.5, BLUE, True)
    # Standard activity initial and final nodes.
    ellipse(slide, 2.72, 2.28, 0.22, 0.22, CHARCOAL, CHARCOAL, 0.7)
    activity_node(slide, "Open admin login", 2.12, 2.63, 1.43, 0.43)
    activity_node(slide, "Submit credentials", 2.12, 3.23, 1.43, 0.43)
    decision_node(slide, "Active\nadmin?", 2.25, 3.84, 1.18, 0.69)
    activity_node(slide, "Sign-in error", 0.82, 3.98, 1.18, 0.42)
    activity_node(slide, "Complete outage form", 2.02, 4.76, 1.65, 0.45)
    decision_node(slide, "Valid fields\nand references?", 2.22, 5.38, 1.25, 0.75)
    activity_node(slide, "Correct input", 0.82, 5.52, 1.18, 0.43)
    activity_node(slide, "Save active outage", 2.03, 6.25, 1.63, 0.43)
    ellipse(slide, 4.45, 6.28, 0.32, 0.32, WHITE, CHARCOAL, 1.2)
    ellipse(slide, 4.51, 6.34, 0.2, 0.2, CHARCOAL, CHARCOAL, 0.7)

    line(slide, 2.83, 2.5, 2.83, 2.63, CHARCOAL, 1, arrow=True)
    line(slide, 2.83, 3.06, 2.83, 3.23, CHARCOAL, 1, arrow=True)
    line(slide, 2.83, 3.66, 2.83, 3.84, CHARCOAL, 1, arrow=True)
    line(slide, 2.25, 4.18, 2.0, 4.18, CHARCOAL, 1, arrow=True)
    text(slide, "No", 1.98, 3.91, 0.28, 0.18, 8.5, CHARCOAL)
    line(slide, 2.83, 4.53, 2.83, 4.76, CHARCOAL, 1, arrow=True)
    text(slide, "Yes", 2.92, 4.54, 0.38, 0.16, 8.5, CHARCOAL)
    line(slide, 2.83, 5.21, 2.83, 5.38, CHARCOAL, 1, arrow=True)
    line(slide, 2.22, 5.75, 2.0, 5.75, CHARCOAL, 1, arrow=True)
    text(slide, "No", 1.96, 5.48, 0.28, 0.18, 8.5, CHARCOAL)
    line(slide, 2.83, 6.13, 2.83, 6.25, CHARCOAL, 1, arrow=True)
    text(slide, "Yes", 2.95, 6.12, 0.38, 0.16, 8.5, CHARCOAL)
    line(slide, 3.66, 6.46, 4.45, 6.46, CHARCOAL, 1, arrow=True)
    line(slide, 1.42, 3.98, 1.42, 3.44, CHARCOAL, 0.8)
    line(slide, 1.42, 3.44, 2.12, 3.44, CHARCOAL, 0.8, arrow=True)
    line(slide, 1.42, 5.95, 1.42, 4.97, CHARCOAL, 0.8)
    line(slide, 1.42, 4.97, 2.02, 4.97, CHARCOAL, 0.8, arrow=True)

    line(slide, 5.12, 1.93, 5.12, 6.78, GRAY, 0.8)
    text(slide, "OUTAGE ER SLICE", 5.45, 1.93, 3.3, 0.22, 9.5, BLUE, True)
    entity(slide, "users", "PK id\nrole, is_active", 5.48, 2.42, 2.0, 0.8)
    entity(slide, "zones", "PK id\nparent_id (nullable)", 10.0, 2.42, 2.0, 0.8)
    entity(slide, "outage_reasons", "PK id\nis_active", 5.48, 5.53, 2.0, 0.8)
    entity(slide, "schedules", "PK id\nzone_id", 10.0, 5.53, 2.0, 0.8)
    entity(slide, "outages", "FK zone_id (required)\nFK schedule_id?; reason_id?; reported_by?", 7.82, 3.92, 2.25, 1.0)
    line(slide, 7.48, 2.82, 8.35, 3.92, CHARCOAL, 0.9)
    line(slide, 10.0, 2.82, 9.65, 3.92, CHARCOAL, 0.9)
    line(slide, 7.48, 5.53, 8.28, 4.92, CHARCOAL, 0.9)
    line(slide, 10.0, 5.53, 9.65, 4.92, CHARCOAL, 0.9)
    text(slide, "1 : many", 7.67, 3.25, 0.8, 0.18, 8, MID)
    text(slide, "1 : many", 9.72, 3.25, 0.8, 0.18, 8, MID)
    text(slide, "optional", 7.55, 5.1, 0.7, 0.18, 8, MID)
    text(slide, "optional", 10.05, 5.1, 0.7, 0.18, 8, MID)


def slide_9(prs):
    slide = prs.slides.add_slide(prs.slide_layouts[6])
    header(
        slide,
        "09 / 10",
        "Requirements & implementation progress",
        "04  |  COMPLETENESS & IMPLEMENTATION",
        "Completed scope is supported by application code and local test evidence.",
    )
    rows = [
        ("FR-01–05", "Public information: area browsing, filters, outage details/history, schedules, notices and language preference", "Public routes, views, controllers and feature tests", "Implemented"),
        ("FR-11–15", "Administrator access and management of areas, reasons, outages, schedules and announcements", "Protected admin routes, controllers, persistence and feature tests", "Implemented"),
        ("Verification", "Automated regression suite for the current application", "34 tests passed; 150 assertions in the local PHPUnit run", "Passed"),
    ]
    table(
        slide,
        0.75,
        2.05,
        [1.35, 4.45, 4.5, 1.55],
        ["Requirement / task", "Completed scope", "Evidence in this project", "Status"],
        rows,
        0.82,
        10.2,
        0.45,
        status_column=3,
    )
    text(
        slide,
        "Implemented means present in the repository. The test result is local verification—not evidence of production deployment, user acceptance or utility approval.",
        0.82,
        5.95,
        11.7,
        0.48,
        10.5,
        MID,
    )


def slide_10(prs):
    slide = prs.slides.add_slide(prs.slide_layouts[6])
    header(
        slide,
        "10 / 10",
        "Requirements remaining",
        "04  |  COMPLETENESS & IMPLEMENTATION",
        "Partial, proposed and out-of-scope items are separated so the remaining work is explicit.",
    )
    rows = [
        ("FR-06", "Audit date/time entry and display; verify consistent Asia/Dhaka handling.", "Timezone and representative date tests across public and admin views.", "Partial"),
        ("FR-07–08", "Add source attribution and verify official utility contact information.", "Maintained source/contact records with a named verification owner.", "Proposed"),
        ("FR-09", "Clarify service-area coverage and data limits in public empty states and notices.", "Reviewed public wording that does not imply exhaustive coverage.", "Partial"),
        ("FR-10", "Decide whether citizen reports, alerts and complaints belong in scope; implement only if approved.", "Documented scope decision; corresponding workflow and tests if accepted.", "Out of scope"),
        ("FR-16–17", "Define pre-publication checks, approval responsibility and correction/stale-record handling.", "Agreed publishing and correction process reflected in the application.", "Proposed"),
        ("NFR-01–09", "Set and verify measurable mobile, accessibility, freshness, privacy, recovery and performance criteria.", "Recorded acceptance results and operational recovery/performance targets.", "Open"),
    ]
    table(
        slide,
        0.75,
        1.98,
        [1.25, 4.15, 4.65, 1.8],
        ["Requirement", "Remaining work", "Evidence / exit condition", "Current status"],
        rows,
        0.61,
        9.7,
        0.43,
        status_column=3,
    )
    text(
        slide,
        "Before production use: validate priorities with residents and relevant utility/content owners. The requirements baseline is proposed and desk-based; research and utility approval are not documented.",
        0.82,
        6.22,
        11.7,
        0.48,
        10,
        MID,
    )


def slide_a1(prs):
    slide = prs.slides.add_slide(prs.slide_layouts[6])
    header(
        slide,
        "A1",
        "Research & elicitation status",
        "APPENDIX  |  EVIDENCE",
        "The requirements document explicitly labels itself a desk-based draft, pending validation.",
    )
    text(slide, "DOCUMENTED EVIDENCE", 0.8, 2.0, 3.3, 0.22, 10, BLUE, True)
    evidence = [
        ("Project README", "Product scope, setup and documented technologies."),
        ("Project source", "Routes, controllers, schema, public/admin workflows and automated tests."),
        ("Requirements baseline", "PowerStat_BD_Requirements.docx, 3 Nov 2026; proposed and desk-based."),
        ("Official sources cited in baseline", "Power Division, DPDC and BPDB sites; their contact information must be rechecked before use."),
    ]
    for i, (heading, detail) in enumerate(evidence):
        y = 2.43 + i * 0.76
        text(slide, heading, 0.82, y, 2.45, 0.27, 12, NAVY, True)
        text(slide, detail, 3.1, y, 3.05, 0.54, 11.2, CHARCOAL)
        if i < len(evidence) - 1:
            line(slide, 0.82, y + 0.63, 6.18, y + 0.63, GRAY, 0.6)

    line(slide, 6.54, 1.98, 6.54, 5.78, GRAY, 0.8)
    text(slide, "NOT CONDUCTED / NOT DOCUMENTED", 6.9, 2.0, 4.5, 0.22, 10, BLUE, True)
    missing = [
        ("Interviews", "No transcripts, participants or interview findings in the project files."),
        ("Questionnaire", "No survey responses or analysis found."),
        ("Observation", "No site visits or observation logs documented."),
        ("Validation", "No user or utility approval; validate priorities and utility context before production."),
    ]
    for i, (heading, detail) in enumerate(missing):
        y = 2.43 + i * 0.76
        text(slide, heading, 6.92, y, 1.7, 0.27, 12, NAVY, True)
        text(slide, detail, 8.55, y, 3.75, 0.54, 11.2, CHARCOAL)
        if i < len(missing) - 1:
            line(slide, 6.92, y + 0.63, 12.38, y + 0.63, GRAY, 0.6)

    text(
        slide,
        "Proposed next step: usability sessions with Bangla-first residents across more than one service territory, then review with relevant content owners.",
        0.82,
        6.18,
        11.8,
        0.46,
        11,
        CHARCOAL,
    )


def slide_a2(prs):
    slide = prs.slides.add_slide(prs.slide_layouts[6])
    header(
        slide,
        "A2",
        "Architecture & database evidence",
        "APPENDIX  |  TECHNICAL DESIGN",
        "Responsibilities and table names follow Laravel routes, models and migrations.",
    )
    text(slide, "REQUEST / DATA FLOW", 0.8, 1.97, 3.2, 0.22, 10, BLUE, True)
    blocks = [
        (0.82, "Browser", "Visitor or admin"),
        (3.22, "Web routes", "Named routes + middleware"),
        (5.62, "Controllers", "Public reads + admin writes"),
        (8.02, "Eloquent models", "Relationships + persistence"),
        (10.42, "Relational DB", "MySQL / MariaDB documented"),
    ]
    for x, title, detail in blocks:
        box(slide, x, 2.45, 2.02, 0.9, WHITE, NAVY, 0.9)
        text(slide, title, x + 0.1, 2.59, 1.82, 0.24, 12, NAVY, True, PP_ALIGN.CENTER)
        text(slide, detail, x + 0.1, 2.9, 1.82, 0.3, 9.7, CHARCOAL, align=PP_ALIGN.CENTER)
    for x in [2.91, 5.31, 7.71, 10.11]:
        line(slide, x, 2.9, x + 0.24, 2.9, CHARCOAL, 0.9, arrow=True)

    text(slide, "MIGRATION-DEFINED ENTITIES", 0.8, 3.8, 4.0, 0.22, 10, BLUE, True)
    rows = [
        ("users", "Account role / activity; reports and authorship"),
        ("zones", "Hierarchical service-area records"),
        ("outage_reasons", "Reusable reason labels"),
        ("load_shedding_schedules", "Area, date/time window and publication status"),
        ("outages", "Area, optional reason/schedule/reporter, status and timestamps"),
        ("announcements", "Body, optional area, publication status and time"),
    ]
    table(slide, 0.82, 4.17, [2.82, 8.9], ["Table", "Purpose in current schema"], rows, 0.36, 10.5, 0.39)
    text(
        slide,
        "No live utility API, subscriber messaging system or public outage-report submission table was found.",
        0.84,
        6.77,
        11.7,
        0.18,
        9,
        MID,
    )


def slide_a3(prs):
    slide = prs.slides.add_slide(prs.slide_layouts[6])
    header(
        slide,
        "A3",
        "Implemented interface",
        "APPENDIX  |  ACTUAL APPLICATION CAPTURES",
        "Screenshots are unaltered captures of the local application; no browser/device frame has been added.",
    )
    text(slide, "PUBLIC HOME PAGE  |  BANGLA DEFAULT", 0.8, 1.92, 6.1, 0.22, 9.5, BLUE, True)
    text(slide, "ADMIN SIGN-IN", 8.45, 1.92, 3.9, 0.22, 9.5, BLUE, True)
    slide.shapes.add_picture(str(HOME_SCREENSHOT), Inches(0.8), Inches(2.28), width=Inches(7.25), height=Inches(4.08))
    slide.shapes.add_picture(str(ADMIN_SCREENSHOT), Inches(8.42), Inches(2.28), width=Inches(4.0), height=Inches(2.25))
    line(slide, 8.28, 2.28, 8.28, 6.35, GRAY, 0.8)
    text(
        slide,
        "Home screen groups area search, outage status, schedules and notices. Zero counts reflect the empty local database.",
        8.45,
        4.78,
        3.9,
        0.72,
        11,
        CHARCOAL,
    )
    text(
        slide,
        "Both captures: local Laravel app, 1600 × 900 viewport. No production data or credentials are displayed.",
        0.82,
        6.62,
        11.6,
        0.22,
        9,
        MID,
    )


def slide_a4(prs):
    slide = prs.slides.add_slide(prs.slide_layouts[6])
    header(
        slide,
        "A4",
        "Testing evidence & sources",
        "APPENDIX  |  VERIFICATION",
        "Automated tests verify repository behavior; they do not prove production readiness or real-world data accuracy.",
    )
    text(slide, "TEST RUN", 0.82, 2.0, 2.0, 0.22, 10, BLUE, True)
    text(slide, "34 / 34 tests passed", 0.82, 2.45, 4.8, 0.5, 24, NAVY, True)
    text(slide, "150 assertions  |  PHPUnit 12.5.39", 0.84, 3.05, 4.8, 0.3, 13, CHARCOAL)
    text(slide, "Run with PHP 8.5.9 and PDO SQLite loaded for the test process.", 0.84, 3.48, 4.85, 0.55, 11, MID)
    line(slide, 5.92, 1.98, 5.92, 5.92, GRAY, 0.8)
    text(slide, "COVERED BEHAVIOR", 6.3, 2.0, 2.6, 0.22, 10, BLUE, True)
    covered = [
        "Admin login, inactive/non-admin denial and rate limiting",
        "Admin resource pages, schedule validation and outage restoration",
        "Public published-content filtering and page rendering",
        "Language preference and model relationships",
    ]
    for i, item in enumerate(covered):
        y = 2.42 + i * 0.47
        text(slide, "•", 6.32, y, 0.18, 0.23, 13, BLUE, True)
        text(slide, item, 6.56, y, 5.7, 0.36, 11.5, CHARCOAL)

    line(slide, 0.82, 4.34, 5.55, 4.34, GRAY, 0.8)
    text(slide, "PROJECT SOURCES", 0.82, 4.62, 2.4, 0.22, 10, BLUE, True)
    sources = [
        "README.md; PowerState_Project_Structure_Report_BN.pdf",
        "PowerStat_BD_Requirements.docx (proposed desk-based draft)",
        "routes/web.php; app/Http/Controllers; app/Models",
        "database/migrations; resources/views; tests/Feature",
    ]
    for i, source in enumerate(sources):
        text(slide, source, 0.84, 5.0 + i * 0.34, 4.9, 0.25, 9.7, CHARCOAL)
    text(
        slide,
        "Not claimed: interviews, load testing, accessibility audit, production monitoring or live-data accuracy checks.",
        6.32,
        4.55,
        5.95,
        0.68,
        11,
        MID,
    )
    text(
        slide,
        "Official utility context and contacts in the requirements draft are dated 3 Nov 2026 and must be rechecked before use.",
        6.32,
        5.5,
        5.95,
        0.7,
        11,
        CHARCOAL,
    )


def build():
    if not HOME_SCREENSHOT.is_file():
        raise FileNotFoundError(f"Expected screenshot asset at {HOME_SCREENSHOT}")
    if not ADMIN_SCREENSHOT.is_file():
        raise FileNotFoundError(f"Expected screenshot asset at {ADMIN_SCREENSHOT}")

    prs = Presentation()
    prs.slide_width = Inches(WIDTH)
    prs.slide_height = Inches(HEIGHT)
    prs.core_properties.title = "PowerStat BD — CSE 3210 Final Project Presentation"
    prs.core_properties.subject = "System Design Lab"
    prs.core_properties.author = "PowerStat BD project team"
    prs.core_properties.keywords = "CSE 3210, PowerStat BD, system design"

    for make_slide in [
        slide_1,
        slide_2,
        slide_3,
        slide_4,
        slide_5,
        slide_6,
        slide_7,
        slide_8,
        slide_9,
        slide_10,
        slide_a1,
        slide_a2,
        slide_a3,
        slide_a4,
    ]:
        make_slide(prs)

    prs.save(OUTPUT)
    print(f"Created {OUTPUT}")
    print(f"Slides: {len(prs.slides)} total (10 main + 4 appendix)")


if __name__ == "__main__":
    build()
