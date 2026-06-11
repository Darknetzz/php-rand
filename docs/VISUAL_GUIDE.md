# Random Data Button - Visual Guide

## Button Appearance

### Default State
```
┌─────────────────────────────────────┬──────┐
│ Input field...                      │  🔀  │
└─────────────────────────────────────┴──────┘
```

### After Click (1 second)
```
┌─────────────────────────────────────┬──────┐
│ Generated random data here          │  ✓   │  (Green)
└─────────────────────────────────────┴──────┘
```

### Hover State
```
┌─────────────────────────────────────┬──────┐
│ Input field...                      │  🔀  │  (Slightly larger)
└─────────────────────────────────────┴──────┘
```

## Layout Examples

### Single-line Input
```
Label: Name
┌─────────────────────────────────────┬──────┐
│ Enter your name...                  │  🔀  │
└─────────────────────────────────────┴──────┘
```

### Number Input
```
Label: Port Number
┌─────────────────────────────────────┬──────┐
│ 8080                                │  🔀  │
└─────────────────────────────────────┴──────┘
```

### Textarea (Multi-line)
```
Label: JSON Data
┌─────────────────────────────────────┬──────┐
│ {                                   │  🔀  │
│   "id": 123,                        │      │
│   "name": "example",                │      │
│   ...                               │      │
│ }                                   │      │
│                                     │      │
└─────────────────────────────────────┴──────┘
```

## Context Examples

### Email Field
```
Placeholder: "Enter email address..."
Generated:   abc12345@example.com
```

### URL Field
```
Placeholder: "Enter URL link..."
Generated:   https://example.com/abc123
```

### IP Address Field
```
Placeholder: "Enter IP address..."
Generated:   192.168.1.100
```

### Hex Hash Field
```
Placeholder: "Enter hex hash value..."
Generated:   a3f7c9e2b8d1f4e6c7a2b9d8e3f1c6a4
```

### JSON Textarea
```
Placeholder: "Enter JSON data..."
Generated:   {
              "id": 742,
              "name": "XkP9mN2qL7",
              "email": "jhk92xpa@test.com",
              "active": true,
              "timestamp": "2025-12-06T10:30:45.123Z"
            }
```

### Code Textarea
```
Placeholder: "Enter code snippet..."
Generated:   function hello() {
              console.log("Hello, World!");
              return true;
            }
```

### General Text
```
Placeholder: "Enter your text here..."
Generated:   Lorem ipsum dolor sit amet consectetur adipiscing elit...
```

## Real Module Examples

### 1. Hash Generator Module
```
Input Text:
┌─────────────────────────────────────┬──────┐
│ Enter text to hash...               │  🔀  │  ← Click here
└─────────────────────────────────────┴──────┘

After click:
┌─────────────────────────────────────┬──────┐
│ Lorem ipsum dolor sit amet...       │  ✓   │
└─────────────────────────────────────┴──────┘
```

### 2. String Tools Module
```
Input String:
┌─────────────────────────────────────┬──────┐
│ Enter your text here...             │  🔀  │  ← Click here
└─────────────────────────────────────┴──────┘

After click:
┌─────────────────────────────────────┬──────┐
│ Lorem ipsum dolor sit amet...       │  ✓   │
└─────────────────────────────────────┴──────┘
```

### 3. Number Generator Module
```
From:
┌─────────────────────────────────────┬──────┐
│ 1                                   │  🔀  │  ← Click generates 1-1000
└─────────────────────────────────────┴──────┘

To:
┌─────────────────────────────────────┬──────┐
│ 100                                 │  🔀  │  ← Click generates 1-1000
└─────────────────────────────────────┴──────┘
```

### 4. Diff Module
```
Old Data:
┌─────────────────────────────────────┬──────┐
│ Enter old/original text...          │  🔀  │  ← Click here
│                                     │      │
│                                     │      │
└─────────────────────────────────────┴──────┘

New Data:
┌─────────────────────────────────────┬──────┐
│ Enter new/modified text...          │  🔀  │  ← Click here
│                                     │      │
│                                     │      │
└─────────────────────────────────────┴──────┘
```

### 5. Encoding Module
```
Base:
┌─────────────────────────────────────┬──────┐
│ Enter text or base encoded...       │  🔀  │  ← Click here
│                                     │      │
└─────────────────────────────────────┴──────┘
```

## Color Scheme (Dark Theme)

### Button States
- **Default**: Gray outline (#6c757d)
- **Hover**: Slightly scaled (1.05x) with smooth transition
- **Success**: Green background (#28a745)
- **Icon**: White (#ffffff)

### Layout
- **Flex Gap**: 8px between input and button
- **Button Width**: Min 38px (fits icon comfortably)
- **Button Height**: Auto (matches input height)
- **Border Radius**: Matches Bootstrap defaults

## Animation Sequence

```
Time: 0ms
┌──────────────┐
│  🔀 Shuffle  │  (Gray outline, white icon)
└──────────────┘
         ↓ [User clicks]
         
Time: 0-50ms
┌──────────────┐
│  🔀 Shuffle  │  (Scale animation, data generation)
└──────────────┘
         ↓
         
Time: 50ms
┌──────────────┐
│  ✓ Success   │  (Green background, white checkmark)
└──────────────┘
         ↓
         
Time: 1000ms
┌──────────────┐
│  🔀 Shuffle  │  (Transition back to default)
└──────────────┘
```

## Responsive Behavior

### Desktop (>768px)
- Input: 100% width minus button width and gap
- Button: Fixed width (38px+)
- Layout: Horizontal (side-by-side)

### Mobile (<768px)
- Input: 100% width minus button width and gap
- Button: Same size (38px+)
- Layout: Horizontal (side-by-side, but may wrap if screen is very small)

### Textarea
- Always maintains side-by-side layout
- Button aligns to top of textarea
- Button height: auto (doesn't stretch)

## Accessibility

### Keyboard Navigation
- Tab order: Input → Button → Next input
- Enter/Space: Activates button
- Focus ring: Visible on keyboard focus

### Screen Readers
- Button has `title` attribute: "Generate random data"
- Icon uses Bootstrap Icons (recognized by screen readers)
- Button type: "button" (prevents form submission)

### ARIA Labels
```html
<button 
  type="button" 
  class="btn btn-sm btn-outline-secondary random-data-btn"
  title="Generate random data"
  aria-label="Generate random data for this field">
  <i class="bi bi-shuffle" aria-hidden="true"></i>
</button>
```

## Browser Compatibility Table

| Feature | Chrome | Firefox | Safari | Edge |
|---------|--------|---------|--------|------|
| Flexbox | ✅ | ✅ | ✅ | ✅ |
| Arrow Functions | ✅ | ✅ | ✅ | ✅ |
| const/let | ✅ | ✅ | ✅ | ✅ |
| Template Literals | ✅ | ✅ | ✅ | ✅ |
| Bootstrap Icons | ✅ | ✅ | ✅ | ✅ |
| jQuery 4.0+ | ✅ | ✅ | ✅ | ✅ |
| CSS Transitions | ✅ | ✅ | ✅ | ✅ |

## Performance Metrics

### Initial Load
- **DOM Queries**: ~50-200 elements (depending on page)
- **Execution Time**: <100ms (typical)
- **Memory Impact**: <1MB (negligible)

### Per Button Click
- **Data Generation**: <5ms
- **DOM Update**: <10ms
- **Animation**: 1000ms (setTimeout)
- **Total**: <1020ms

### Network Impact
- **Zero** - All processing is client-side
- No API calls
- No external data fetching

---

**Tip**: For the best experience, ensure your browser has JavaScript enabled and is up to date!
