export default function Loading() {
  return (
    <div className="mx-auto max-w-2xl px-4 py-10">
      <div className="mb-6 h-7 w-56 animate-pulse rounded bg-surface-container" />
      <div className="animate-pulse space-y-5 rounded-2xl border border-outline-variant/20 bg-surface-container-low p-6">
        <div className="h-10 w-full rounded-xl bg-surface-container" />
        <div className="h-24 w-full rounded-xl bg-surface-container" />
        <div className="grid grid-cols-2 gap-4">
          <div className="h-10 rounded-xl bg-surface-container" />
          <div className="h-10 rounded-xl bg-surface-container" />
        </div>
        <div className="h-12 w-full rounded-xl bg-surface-container" />
      </div>
    </div>
  );
}
