'use client';

/**
 * Reusable user avatar component.
 * Shows uploaded photo when available, otherwise renders a deterministic
 * initials-based colored circle (same algorithm as AvatarHelper.php).
 */

const COLORS: { bg: string; text: string }[] = [
  { bg: '#48BBE2', text: '#fff' },   // primary sky blue
  { bg: '#102F4B', text: '#fff' },   // navy
  { bg: '#4ECDC4', text: '#fff' },   // teal
  { bg: '#96CEB4', text: '#fff' },   // sage green
  { bg: '#DDA0DD', text: '#fff' },   // plum
  { bg: '#FF6B6B', text: '#fff' },   // coral
  { bg: '#F7DC6F', text: '#102F4B' },// gold
  { bg: '#BB8FCE', text: '#fff' },   // lavender
  { bg: '#85C1E9', text: '#102F4B' },// light blue
  { bg: '#A3E4D7', text: '#102F4B' },// mint
];

function getInitials(name: string): string {
  const words = name.trim().split(/\s+/).filter(Boolean);
  if (words.length >= 2) {
    return (words[0][0] + words[words.length - 1][0]).toUpperCase();
  }
  return name.slice(0, 2).toUpperCase();
}

interface UserAvatarProps {
  name: string;
  userId: number;
  avatarUrl: string | null;
  /** Tailwind size classes e.g. "h-10 w-10" */
  sizeClass?: string;
  /** Rounded class — default "rounded-lg" */
  roundedClass?: string;
  /** Font size for initials — default "text-sm" */
  fontClass?: string;
}

export function UserAvatar({
  name,
  userId,
  avatarUrl,
  sizeClass = 'h-10 w-10',
  roundedClass = 'rounded-lg',
  fontClass = 'text-sm',
}: UserAvatarProps) {
  const color = COLORS[userId % COLORS.length];
  const initials = getInitials(name || '?');

  if (avatarUrl) {
    return (
      // eslint-disable-next-line @next/next/no-img-element
      <img
        src={avatarUrl}
        alt={name}
        className={`${sizeClass} ${roundedClass} object-cover`}
      />
    );
  }

  return (
    <div
      className={`${sizeClass} ${roundedClass} ${fontClass} flex shrink-0 items-center justify-center font-bold leading-none`}
      style={{ backgroundColor: color.bg, color: color.text }}
      aria-label={name}
    >
      {initials}
    </div>
  );
}
