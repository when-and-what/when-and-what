export function sortEvents(a, b) {
    const da = new Date(a.date);
    const db = new Date(b.date);
    if (da < db) return -1;
    if (da > db) return 1;
    return 0;
}

export function buildGroupedFeed(events) {
    const sorted = [...events].sort(sortEvents);
    const result = [];
    let currentGroup = null;

    for (const e of sorted) {
        if (e.collapsible) {
            if (currentGroup && currentGroup.color === e.color) {
                currentGroup.events.push(e);
            } else {
                currentGroup = {
                    isGroup: true,
                    color: e.color,
                    groupLabel: e.groupLabel,
                    groupIcon: e.groupIcon,
                    events: [e],
                };
                result.push(currentGroup);
            }
        } else {
            currentGroup = null;
            result.push(e);
        }
    }

    const periods = ['Morning', 'Afternoon', 'Evening'];
    const getPeriod = (date) => {
        const hour = new Date(date).getHours();
        if (hour < 12) return 0;
        if (hour < 18) return 1;
        return 2;
    };
    const getDate = (item) => item.isGroup ? item.events[0].date : item.date;

    const withDividers = [];
    let lastPeriod = -1;
    for (const item of result) {
        const period = getPeriod(getDate(item));
        if (period !== lastPeriod) {
            withDividers.push({ isDivider: true, label: periods[period] });
            lastPeriod = period;
        }
        withDividers.push(item);
    }

    return withDividers;
}
